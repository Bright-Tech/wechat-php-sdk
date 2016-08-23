<?php
namespace bright_tech\wechat\core;

class Http
{

    public $outputStream = false;

    public $response;

    public $config = [];

    /**
     * @param $method
     * @param $uri
     * @param array $headers
     * @param string $body
     * @param float $httpVersion
     * @return mixed
     * @throws \Exception
     */
    public function write($method, $uri, $headers = [], $body = '', $httpVersion = 1.1)
    {
        $curlHandle = curl_init();

        // set URL
        curl_setopt($curlHandle, CURLOPT_URL, $uri);
        // ensure correct curl call
        $curlValue = true;
        switch ($method) {
            case 'GET':
                $curlMethod = CURLOPT_HTTPGET;
                break;
            case 'POST':
                $curlMethod = CURLOPT_POST;
                break;
            case 'PUT':
                // There are two different types of PUT request, either a Raw Data string has been set
                // or CURLOPT_INFILE and CURLOPT_INFILESIZE are used.
                if (is_resource($body)) {
                    $this->config['curloptions'][CURLOPT_INFILE] = $body;
                }
                if (isset($this->config['curloptions'][CURLOPT_INFILE])) {
                    // Now we will probably already have Content-Length set, so that we have to delete it
                    // from $headers at this point:
                    if (!isset($headers['Content-Length']) && !isset($this->config['curloptions'][CURLOPT_INFILESIZE])) {
                        throw new AdapterException\RuntimeException('Cannot set a file-handle for cURL option CURLOPT_INFILE' . ' without also setting its size in CURLOPT_INFILESIZE.');
                    }
                    if (isset($headers['Content-Length'])) {
                        $this->config['curloptions'][CURLOPT_INFILESIZE] = (int)$headers['Content-Length'];
                        unset($headers['Content-Length']);
                    }
                    if (is_resource($body)) {
                        $body = '';
                    }
                    $curlMethod = CURLOPT_UPLOAD;
                } else {
                    $curlMethod = CURLOPT_CUSTOMREQUEST;
                    $curlValue = "PUT";
                }
                break;
            case 'PATCH':
                $curlMethod = CURLOPT_CUSTOMREQUEST;
                $curlValue = "PATCH";
                break;
            case 'DELETE':
                $curlMethod = CURLOPT_CUSTOMREQUEST;
                $curlValue = "DELETE";
                break;
            case 'OPTIONS':
                $curlMethod = CURLOPT_CUSTOMREQUEST;
                $curlValue = "OPTIONS";
                break;
            case 'TRACE':
                $curlMethod = CURLOPT_CUSTOMREQUEST;
                $curlValue = "TRACE";
                break;
            case 'HEAD':
                $curlMethod = CURLOPT_CUSTOMREQUEST;
                $curlValue = "HEAD";
                break;
            default:
                // For now, through an exception for unsupported request methods
                throw new AdapterException\InvalidArgumentException("Method '$method' currently not supported");
        }
        if (is_resource($body) && $curlMethod != CURLOPT_UPLOAD) {
            throw new \Exception("Streaming requests are allowed only with PUT");
        }
        // get http version to use
        $curlHttp = ($httpVersion == 1.1) ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_1_0;
        // mark as HTTP request and set HTTP method
        curl_setopt($curlHandle, CURLOPT_HTTP_VERSION, $curlHttp);
        curl_setopt($curlHandle, $curlMethod, $curlValue);
        // Set the CURLINFO_HEADER_OUT flag so that we can retrieve the full request string later
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);
        if ($this->outputStream) {
            // headers will be read into the response
            curl_setopt($curlHandle, CURLOPT_HEADER, false);
            curl_setopt($curlHandle, CURLOPT_HEADERFUNCTION, [
                $this,
                "readHeader"
            ]);
            // and data will be written into the file
            curl_setopt($curlHandle, CURLOPT_FILE, $this->outputStream);
        } else {
            // ensure actual response is returned
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        }
        // Treating basic auth headers in a special way
        if (array_key_exists('Authorization', $headers) && 'Basic' == substr($headers['Authorization'], 0, 5)) {
            curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curlHandle, CURLOPT_USERPWD, base64_decode(substr($headers['Authorization'], 6)));
            unset($headers['Authorization']);
        }
        // set additional headers
        if (!isset($headers['Accept'])) {
            $headers['Accept'] = '';
        }
        $curlHeaders = [];
        foreach ($headers as $key => $value) {
            $curlHeaders[] = $key . ': ' . $value;
        }
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $curlHeaders);
        /**
         * Make sure POSTFIELDS is set after $curlMethod is set:
         *
         * @link http://de2.php.net/manual/en/function.curl-setopt.php#81161
         */
        if (in_array($method, [
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
            'OPTIONS'
        ], true)) {
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $body);
        } elseif ($curlMethod == CURLOPT_UPLOAD) {
            // this covers a PUT by file-handle:
            // Make the setting of this options explicit (rather than setting it through the loop following a bit lower)
            // to group common functionality together.
            curl_setopt($curlHandle, CURLOPT_INFILE, $this->config['curloptions'][CURLOPT_INFILE]);
            curl_setopt($curlHandle, CURLOPT_INFILESIZE, $this->config['curloptions'][CURLOPT_INFILESIZE]);
            unset($this->config['curloptions'][CURLOPT_INFILE]);
            unset($this->config['curloptions'][CURLOPT_INFILESIZE]);
        }
        // set additional curl options
        if (isset($this->config['curloptions'])) {
            foreach ((array)$this->config['curloptions'] as $k => $v) {
                if (!in_array($k, $this->invalidOverwritableCurlOptions)) {
                    if (curl_setopt($curlHandle, $k, $v) == false) {
                        throw new AdapterException\RuntimeException(sprintf('Unknown or erroreous cURL option "%s" set', $k));
                    }
                }
            }
        }
        // send the request
        $response = curl_exec($curlHandle);
        // if we used streaming, headers are already there
        if (!is_resource($this->outputStream)) {
            $this->response = $response;
        }
        $request = curl_getinfo($curlHandle, CURLINFO_HEADER_OUT);
        $request .= $body;
        if (empty($this->response)) {
            throw new \Exception("Error in cURL request: " . curl_error($curlHandle));
        }

        curl_close($curlHandle);
        return $response;
    }
}

