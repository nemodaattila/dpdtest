<?php

namespace rest;

class CustomHttpResponse
{
    private array $headers=[];
    private mixed $data;
    private bool $jsonEncoded;

    /**
     * HttpResponse constructor.
     * @param array $headers
     * @param $data
     * @param bool $jsonEncoded
     */
    public function __construct(array $headers, mixed $data, bool $jsonEncoded=true)
    {
        $this->headers = $headers;
        $this->data = $data;
        $this->jsonEncoded = $jsonEncoded;
    }

    public function send()
    {
        $this->compile();
    }

    public function compile()
    {
        foreach ($this->headers as $header)
        {
            header($header);
        }
        if ($this->jsonEncoded)
        {
            echo json_encode($this->data);
        }
        else echo $this->data;
        die();
    }
}
