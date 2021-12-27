<?php
 
/**
 * Classe responsável pela resposta da API ao cliente
 */
class api_response
{
    private $data;
    
    private $available_methods = ['GET', 'POST'];

    /**
     * Método construtor da API,
     * define o inicio do array de informações
     * que será devolvido ao cliente
     */
    public function __construct()
    {
        $this->data = [];
    }

    //=================================================================

    /**
     * Método da API que verifica se o método de requisição
     * que o usuario deseja utilizar é valido
     * 
     * @param string $method
     * @return bool
     */
    public function check_method($method)
    {
        //Checando se o método é válido
        return in_array($method, $this->available_methods);

    }
    //=================================================================

    /**
     * Método da API que define o metodo de requisição de dados
     *
     * @param string $method
     * @return void
     */
    public function set_method($method)
    {
        //setando metodo de resposta
        $this->data['method'] = $method; 
    }

    //=================================================================

    /**
     * Método que retorna qual o metodo http que foi realizado a requisição
     *
     * @return void
     */
    public function get_method()
    {
        //retornando metodo
        return $this->data['method'];
    }
    //=================================================================

    /**
     * Método que indica para a API qual o endpoint que será utilizado
     *
     * @param string $endpoint
     * @return void
     */
    public function set_endpoint($endpoint)
    {
        //setando endpoint de busca
        $this->data['endpoint'] = $endpoint;
    }
    //=================================================================

    /**
     * Método que retorna o endpoint que a API está utilizando
     *
     * @return void
     */
    public function get_endpoint()
    {
        //retornando endpoint
        return $this->data['endpoint'];
    }

    //=================================================================

    /**
     * Método que adiciona ao array de retorno das informações 
     * os valores referentes a busca na API
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    function add_to_data($key, $value)
    {
        //adicionando valor ao retorno
        $this->data[$key] = $value;
    }

    /**
     * Método da API que define uma mensagem de erro
     *
     * @param string $message
     * @return void
     */
    public function api_request_error($message = '')
    {
        $this->data['data'] = ['status'=>'Error','message'=>$message];
      
        $this->send_response();
    }

    //=================================================================

    /**
     * Método da API que retorna a resposta com o array de dados no formato json
     *
     * @return void
     */
    public function send_response()
    {
        header('Content-Type:application/json');
        echo json_encode($this->data);
        die;
    }
}