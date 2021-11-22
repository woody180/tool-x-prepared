<?php

trait RequestTrait {
    
    private $getMethod;
    private $request;
    private $urlParams;
    private $url;
    private $isDone = false;
    
    
    // Construct request url data...
    protected function constructRequest() {
        
        // Get requested url
        $urlArr = explode(URLROOT, CURRENT_URL);
        $url = isset($urlArr[1]) ? $urlArr[1] : '/';
        $url = $url != '/' ? ltrim(rtrim($url, '/'), '/') : '/';
        $this->url = filter_var($url, FILTER_SANITIZE_STRING);

        // Url parameters
        $this->urlParams = $this->url == '/' ? null : explode('/', $this->url);
        
        // Get request method
        $this->getMethod = strtolower($_SERVER["REQUEST_METHOD"]);

        $methords = ['put', 'patch', 'delete'];

        foreach ($_POST as $key => $val) {
            if ($key == '_method' && gettype($val) == 'string' && in_array(strtolower($val), $methords)) {
                $this->getMethod = strtolower($val);
                unset($_POST['_method']);
            }
        }

        // Check if not ajax
        if (!$this->isAjax()) {
            setFlashData('previous_url', urlSegments());
        }
        $this->body();
    }


    // Check if request is ajax
    public function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0) {
            return true;
        }

        return false;
    }
    

    // Get url segment
    public function getSegment(int $index, bool $withQeuryString = false) {
        
        $param = $this->urlParams[$index - 1] ?? null;
        
        if (!$param) return null;
        
        if ($withQeuryString) {
            return $param;
        } else {
            return explode('?', $param)[0];
        }
    }


    public function body(string $index = null) {
                
        // Request data
        $reqString = file_get_contents('php://input');
        $data = [];

        if (!empty($reqString) && isJSON($reqString)) {
            $data = json_decode($reqString, true);
        } else {
            parse_str($reqString, $data);
        }

        if (isset($data['_method'])) unset($data['_method']);

        // Append post request to body
        foreach ($_POST as $key => $val)
            $data[$key] = $val;


        if (CSRF_PROTECTION && $this->getMethod() === 'post' && !$this->isDone) {
                    
            // If token inside the request body
            if (!isset($data['csrf_token'])) return abort(['code' => 403]);

            // Compare tokens
            if ($data['csrf_token'] != $_SESSION['csrf_token']) return abort(['code' => 403]);

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $this->isDone = true;
        }

        if (isset($data['csrf_token'])) unset($data['csrf_token']);


        if ($index)
            return $data[$index] ?? null;
        
        return $data;
        
    }


    // Url
    public function url() {

        $res = preg_replace('/[\/]?\?.*/', '', $this->url);
        
        if (!strlen($res)) return '/';
        return $res;
    }


    // Url segments array
    public function urlSegments() {
        return $this->urlParams;
    }


    // Get method
    public function getMethod() {
        return $this->getMethod;
    }


    // Get query
    public function query(string $key = null) {
        // Query string
        preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
        $queryStr = null;

        if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
            parse_str($queryString[1][0], $queryArr);
            $queryStr = $queryString[0][0];
        } else {
            $queryArr = null;
        }

        if ($key) {
            return $queryArr[$key] ?? null;
        } else {
            return $queryArr;
        }

    }


    // Get query string
    public function queryStr() {
        // Query string
        preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
        $queryStr = null;

        if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
            parse_str($queryString[1][0], $queryArr);
            $queryStr = $queryString[0][0];
        } else {
            $queryArr = null;
        }

        return $queryStr;
    }


    // Get files
    public function files(string $key = null) {
        if ($key) {
            $this->file = $_FILES[$key] ?? null;
            return $this;
        }
        $this->file = $_FILES;
        return $this;
    }


    public function show(string $attr = null) {
        if ($attr) return $this->file[$attr] ?? null;
        return $this->file;
    }


    public function upload(string $filePath, string $fileName = null) {

        // If multiple files
        if (isset($this->file['name']) && is_array($this->file['name'])) {
            $uploadedFiles = [];
            foreach ($this->file['tmp_name'] as $i => $tempName) {

                if ($fileName) {
                    $newFile = $filePath . '/' . $fileName . '.' . pathinfo($this->file['name'][$i], PATHINFO_EXTENSION);
                    if (file_exists($newFile)) die('File already exists in this directory');
                } else {
                    $newFile = $filePath . '/' . $this->generateRandomString() . '_' . basename($this->file['name'][$i]);
                }
                move_uploaded_file($tempName, $newFile);
                array_push($uploadedFiles, $newFile);
            }
            return $uploadedFiles;
        } else {

            if ($fileName) {
                $newFile = $filePath . '/' . $fileName . '.' . pathinfo($this->file['name'], PATHINFO_EXTENSION);
                if (file_exists($newFile)) die('File already exists in this directory');
            } else {
                $newFile = $filePath . '/' . $this->generateRandomString() . '_' . basename($this->file['name']);
            }
            
            move_uploaded_file($this->file['tmp_name'], $newFile);

            return $newFile;
        }
    }


    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
    // Request object
    private function getRequest() {

        // Construct request
        $this->constructRequest();

        return $this;
    }
}
