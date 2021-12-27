<?php

use function PHPSTORM_META\type;

/**
 * Classe responsável pela construção dos endpoints da API
 */
class api_logic
{
    private $endpoint;
    private $params;

    /**
    * Método construtor, recebe qual endpoint o cliente esta
    * tentando acessar e os parametros necessários
    *
    * @param string $endpoint
    * @param array $params
    */
    public function __construct($endpoint, $params = null)
    {
        //define os atributos da classe
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    /**
     * Método que valida se o endpoint que o cliente deseja acessar
     * existe na API
     *
     * @return bool
     */
    public function endpoint_exists()
    {
        //verificar se o endpoint é valido para API
        return method_exists($this, $this->endpoint);
    }

    /**
     * Método que valida os parametros necessários para um endpoint.
     * se eles foram passados, se possuem valor e tipo corretos
     *
     * @param array $params
     * @return string
     */
    private function validate_params($params = [])
    {
        $message_error = '';

        foreach($params as $param)
        {
            $valueSearch = $param['value'];
            $valueType = $param['type'];

            //Verifica se o parametro necessário existe no array de parametros
            if(key_exists($valueSearch, $this->params))
            {
                $value = $this->params[$valueSearch];

                //Verifica se esse parametro não é nulo nem vazio
                if(!empty($value) && !is_null($value))
                {
                    //valida parametros de String
                    if($valueType == 'String')
                    {
                        if(!is_string($value))
                        {
                            $message_error = "O parametro '$valueSearch' precisa conter uma String";
                            break; 
                        }
                    }
                    else if($valueType == 'int')
                    {
                        if(!filter_var($value,FILTER_VALIDATE_INT))
                        {
                            $message_error = "O parametro '$valueSearch' precisa conter um número inteiro";
                            break;
                        }
                    }
                    else if($valueType == 'bool')
                    {
                        //true e false funciona, mas qualquer coisa que não seja true ele lê como false
                        if(filter_var($value,FILTER_VALIDATE_BOOLEAN)==true || filter_var($value,FILTER_VALIDATE_BOOL)==false)
                        {
                            continue;
                        }
                        else
                        {
                            $message_error = "O parametro '$valueSearch' precisa conter um booleano";
                            break;
                        }
                    }
                    else if($valueType == 'email')
                    {
                        if(!filter_var($value,FILTER_VALIDATE_EMAIL))
                        {
                            $message_error = "O parametro '$valueSearch' precisa conter um endereço de email";
                            break;
                        }
                    }
                }
                else
                {
                    $message_error = "O parametro '$valueSearch' é obrigatório e não possui valor";
                    break;
                }
            }
            else{
                $message_error = "O parametro '$valueSearch' é obrigatório e não foi informado";
                break;
            }
        }

        return $message_error;
        
    }


//---------------------------------------------------------------------
//                          Endpoints
//---------------------------------------------------------------------

    /**
     * Endpoint que retorna o status atual da API
     *
     * @return array
     */
    public function status()
    {
        $data = ['status'=>'Success',
                 'message' => 'API está funcionando'
                ]; 

        return $data;
    }

    /**
     * Endpoint que retorna o total de registros ativos e inativos
     * 
     * @return array
     */
    public function get_totals()
    {
        $db =  new database();
        $results = $db->EXE_QUERY(
        "   SELECT 'Clientes ativos' AS 'registros', COUNT(*) Total FROM clientes WHERE deleted_at IS NULL UNION ALL
            SELECT 'Produtos ativos' AS 'registros', COUNT(*) Total FROM produtos WHERE deleted_at IS NULL UNION all
            SELECT 'Clientes inativos' AS 'registros', COUNT(*) Total FROM clientes WHERE deleted_at IS NOT NULL UNION ALL
            SELECT 'Produtos inativos' AS 'registros', COUNT(*) Total FROM produtos WHERE deleted_at IS NOT NULL 
        ");

                
        $data = ['status'=>'Success',
        'message' => 'API está funcionando',
        'results'=>$results
        ];

        return $data;
    }

//---------------------------------------------------------------------
//                          Clientes
//---------------------------------------------------------------------

    /**
     * Endpoint que retorna todos os clientes cadastrados no
     * banco de dados.
     * Parâmetros da requisição:
     * only_active (opcional)
     *
     * @return array
     */
    public function get_all_clients()
    {
        $sql =  'SELECT * FROM CLIENTES WHERE 1 ';

        //checar se o parametro only_active existe e é verdadeiro
        if(key_exists('only_active', $this->params))
        {
            //compara a string recebida como booleana
            if(filter_var($this->params['only_active'],FILTER_VALIDATE_BOOLEAN) == true)
            {
                $sql.="AND deleted_at IS NULL";
            }
        }

        $db = new database();

        $results = $db->EXE_QUERY($sql);

        $data = ['status'=>'Success',
                 'message' => 'API está funcionando',
                 'results'=>$results
                ];
        
        return $data;
    }

    /**
     * Endpoint que retorna um cliente especifico.
     * Parâmetros da requisição: 
     * id(obrigatório) 
     *
     * @return array
     */
    public function get_client()
    {
        $parametros = [
            ['value'=>'id', 'type'=>'int']
        ];

        $message_error = $this->validate_params($parametros);

        if($message_error=='')
        {
            $sql = "SELECT * FROM clientes WHERE 1";

            $sql.=" AND id_cliente = ". intval($this->params['id']);
    
            $db = new database();
    
            $results = $db->EXE_QUERY($sql);
    
            $data = ['status'=>'Success',
                    'message' => 'API está funcionando',
                    'results'=>$results
                    ];
        
            return $data;
        }

        //caso os requisitos não sejam atendidos retorna o erro
        $data['status'] ='Error';
        $data['message']=$message_error;
        return $data;
      
    }

    /**
     * Endpoint de criação de um cliente.
     * Parâmetros da requisição:
     * nome(obrigatório),
     * email(obrigatório),
     * telefone(obrigatório)
     *
     * @return array
     */
    public function create_client()
    {
        //parametros necessários para execução do método
        $parametros = [
            ['value'=>'nome','type'=>'String'],
            ['value'=>'email','type'=>'email'],
            ['value'=>'telefone','type'=>'String']
        ];

        //Validando parametros recebidos pela API
        $message_error = $this->validate_params($parametros);

        //parametros validados
        if($message_error =='')
        {
            $db = new database();

            $param = [
                ':email'=>$this->params['email']
            ];

            //verificar se ja existe um cliente ativo com mesmo email
            $results = $db->EXE_QUERY('SELECT id_cliente FROM clientes 
            WHERE email=:email AND deleted_at IS NULL', $param);

            if(count($results) != 0)
            {
                $message_error = "Email ja cadastrado no sistema";
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
            }
            else
            {
                //adicionando o cliente ao banco de dados
                $param = [
                    ':nome'=>$this->params['nome'],
                    ':email'=>$this->params['email'],
                    ':telefone'=>$this->params['telefone']
                ];

            
                $db->EXE_QUERY("INSERT INTO CLIENTES VALUES(
                    0,
                    :nome,
                    :email,
                    :telefone, 
                    NOW(),
                    NOW(), 
                    NULL
                )",$param);
                
                $data = [   'status'=>'Success',
                            'message' => 'Novo cliente adicionado com sucesso',
                            'results'=>[]
                        ];
            }

           
        }
        else
        {
            $data = [   'status'=>'Error',
                        'message' => $message_error,
                        'results'=>[]
                    ];
        }

        return  $data;
    }

    /**
     * Endpoint de exclusão de um cliente
     * Parâmetros da requisição:
     * id(obrigatório)
     *
     * @return array
     */
    public function delete_client()
    {
        //parametros necessários para execução do método
        $parametros = [
        ['value'=>'id','type'=>'int']
        ];

        //Validando parametros recebidos pela API
        $message_error = $this->validate_params($parametros);

        //Verificando se houve erro na passagem de parametros
        if($message_error == '')
        {
            $db = new database();
            $param = [
                ':id'=>$this->params['id']
            ];

            //busca padrão - registros que não sofreram soft delete
            $sql_search = "SELECT id_cliente FROM clientes 
            WHERE id_cliente=:id AND deleted_at IS NULL";

            //deleção padrão (soft delete)
            $sql_delete='UPDATE clientes SET deleted_at = NOW() 
            WHERE id_cliente=:id';

            $message ='Cliente excluido com sucesso(Soft Delete)';

            //se opção hard_delete existir
            if(key_exists('hard_delete', $this->params))
            {
                //verificar se hard_delete é verdadeiro
                if(filter_var($this->params['hard_delete'],FILTER_VALIDATE_BOOLEAN) == true)
                    {
                        //busca por todos os registros
                        $sql_search = "SELECT id_cliente FROM clientes 
                        WHERE id_cliente=:id";

                        $sql_delete='DELETE FROM clientes 
                        WHERE id_cliente=:id';

                        $message ='Cliente excluido com sucesso(Hard Delete)';
                    }
            }

            //verificar se no banco de dados existe o eliente que será excluido
            $results = $db->EXE_QUERY($sql_search, $param);

            //Se o Cliente existir 
            if(count($results) == 1)
            {
                //Excluindo registro
                $results = $db->EXE_QUERY($sql_delete, $param);

                $data = [   'status'=>'Success',
                            'message' => $message,
                            'results'=>[]
                        ];
            }
            else
            {
                $message_error = "Cliente inexistente no sistema";
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
            }
        }
        else
        {
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
        }

        return $data;
    }

    /**
     * Endpoint de alteração de um cliente
     * Parâmetros da requisição:
     * id(obrigatório)
     * nome(obrigatório)
     * email(obrigatório)
     * telefone(obrigatório)
     *
     * @return void
     */
    public function update_client()
    {
        $parametros = [
            ['value'=>'id','type'=>'int'],
            ['value'=>'nome','type'=>'String'],
            ['value'=>'email','type'=>'email'],
            ['value'=>'telefone','type'=>'String']
            ];
    
            //Validando parametros recebidos pela API
            $message_error = $this->validate_params($parametros);
    
            //Verificando se houve erro na passagem de parametros
            if($message_error == '')
            {
                $param = [
                    ':id'=>$this->params['id'],
                ];

                $db = new database();

                $sql_search = "SELECT id_cliente FROM clientes 
                WHERE id_cliente=:id AND deleted_at IS NULL";

                $results = $db->EXE_QUERY($sql_search, $param);

                //Se o Cliente existir 
                if(count($results) == 1)
                {
                    $param = [
                        ':id'=>$this->params['id'],
                        ':email'=>$this->params['email'],
                    ];

                    $sql_search_email = "SELECT id_cliente FROM clientes 
                    WHERE email=:email AND id_cliente !=:id AND deleted_at IS NULL";
    
                    $results = $db->EXE_QUERY($sql_search_email, $param);
                    if(count($results)==0)
                    {
                        $param = [
                            ':id'=>$this->params['id'],
                            ':nome'=>$this->params['nome'],
                            ':email'=>$this->params['email'],
                            ':telefone'=>$this->params['telefone']
                        ];

                        $sql_update = "UPDATE CLIENTES SET nome =:nome, email=:email,
                         telefone=:telefone, updated_at = now() 
                        WHERE id_cliente=:id AND deleted_at IS NULL";
        
                        $results = $db->EXE_QUERY($sql_update, $param);
        
                        $data = [   'status'=>'Success',
                        'message' => 'Cliente atualizado com sucesso',
                        'results'=>[]
                        ];
                    }
                    else
                    {
                        $data = ['status'=>'Error',
                        'message' => 'Email ja utilizado por outro cliente',
                        'results'=>[]
                        ];
                    }

                }
                else
                {
                    $data = [   'status'=>'Error',
                    'message' => 'Cliente inexistente no sistema',
                    'results'=>[]
                    ];
                }

            }
            else
            {
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
            }

        return $data;
    }

//---------------------------------------------------------------------
//                          Produtos
//---------------------------------------------------------------------
    /**
     * Endpoint que retorna todos os produtos cadastrados
     * no banco de dados.
     * Parâmetros da requisição:
     * only_active(opcional)
     *
     * @return array
     */
    public function get_all_products()
    {
        $db = new database();
        $sql = "SELECT * FROM PRODUTOS WHERE 1";

        if(key_exists('only_active', $this->params))
        {
            //compara a string recebida como booleana
            if(filter_var($this->params['only_active'],FILTER_VALIDATE_BOOLEAN) == true)
            {
                $sql.=" AND deleted_at IS NULL";
            }
        }

        $results = $db->EXE_QUERY($sql);
        
        $data = ['status'=>'Success',
                 'message' => 'API está funcionando',
                 'results'=>$results
                ];
        
        return $data;
    }

    /**
     * Endpoint que retorna um produto especifico.
     * Parâmetros da requisição:
     * id(obrigatório)
     *
     * @return array
     */
    public function get_product()
    {
        $parametros = [
            ['value'=>'id', 'type'=>'int']
        ];

        $message_error = $this->validate_params($parametros);

        if($message_error=='')
        {
            $sql = "SELECT * FROM produtos WHERE 1";

            $sql.=" AND id_produto = ". intval($this->params['id']);

            $db = new database();

            $results = $db->EXE_QUERY($sql);

            $data = ['status'=>'Success',
                    'message' => 'API está funcionando',
                    'results'=>$results
                    ];

            return $data;
        }

        //caso os requisitos não sejam atendidos retorna o erro
        $data['status'] ='Error';
        $data['message']=$message_error;
        $data['results']=[];

        return $data;
    }

    /**
     * Endpoint que retorna todos os produtos cadastrados no 
     * banco de dados que possuam quantidade em estoque igual a 0
     *
     * @return array
     */
    public function get_all_products_without_stock()
    {
        $db = new database();
        $sql = "SELECT * FROM PRODUTOS WHERE quantidade <= 0";

        $results = $db->EXE_QUERY($sql);
        
        $data = ['status'=>'Success',
                 'message' => 'API está funcionando',
                 'results'=>$results
                ];
        
        return $data;
    }

    /**
     * Endpoint de criação de um produto.
     * Parâmetros da requisição:
     * produto(obrigatório),
     * quantidade(obrigatório)
     *
     * @return void
     */
    public function create_product()
    {
        $parametros = [
            ['value'=>'produto','type'=>'String'],
            ['value'=>'quantidade','type'=>'int']
        ];

        $message_error = $this->validate_params($parametros);

        //parametros validados
        if($message_error =='')
        {
            $param = [
                ':produto'=>$this->params['produto']
            ];

            $db = new database();

            //validar se o produto ja existe no banco de dados
            $sql = "SELECT id_produto FROM produtos 
            WHERE produto = :produto";
            
            $result = $db->EXE_QUERY($sql,$param);

            //verificando se existe resultado na consulta
            if(count($result)!=0)
            {
                $message_error = 'Ja existe um produto com mesmo nome cadastrado';
            
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
            }
            else
            {
                $param = [
                    ':produto'=>$this->params['produto'],
                    ':quantidade'=>$this->params['quantidade']
                ];

                //Cadastra o novo produto no banco de dados
                $sql ="INSERT INTO PRODUTOS
                VALUES(0, :produto, :quantidade, NOW(), NOW(), NULL)";

                $db->EXE_QUERY($sql,$param);
                
                $data = [   'status'=>'Success',
                            'message' => 'Novo Produto adicionado com sucesso',
                            'results'=>[]
                        ];
            }
        }
        else
        {
            $data = [   'status'=>'Error',
                        'message' => $message_error,
                        'results'=>[]
                    ];
        }

        return  $data;
    }

    /**
     * Endpoint de exclusão de um produto
     * Parâmetros da requisição:
     * id(obrigatório)
     * hard_delete(opcional)
     *
     * @return void
     */
    public function delete_product()
    {
        //parametros necessários para execução do método
        $parametros = [
            ['value'=>'id','type'=>'int']
        ];

        //Validando parametros recebidos pela API
        $message_error = $this->validate_params($parametros);

        //Verificando se houve erro na passagem de parametros
        if($message_error == '')
        {
            $db = new database();
            $param = [
                ':id'=>$this->params['id']
            ];

            //busca padrão - registros que não sofreram soft delete
            $sql_search = "SELECT id_produto FROM produtos 
            WHERE id_produto=:id AND deleted_at IS NULL";

            //deleção padrão (soft delete)
            $sql_delete='UPDATE produtos SET deleted_at = NOW() 
            WHERE id_produto=:id';

            $message ='Produto excluido com sucesso(Soft Delete)';

            //se opção hard_delete existir
            if(key_exists('hard_delete', $this->params))
            {
                //verificar se hard_delete é verdadeiro
                if(filter_var($this->params['hard_delete'],FILTER_VALIDATE_BOOLEAN) == true)
                    {
                        //busca por todos os registros
                        $sql_search = "SELECT id_produto FROM produtos 
                        WHERE id_produto=:id";

                        $sql_delete='DELETE FROM produtos 
                        WHERE id_produto=:id';

                        $message ='Produto excluido com sucesso(Hard Delete)';
                    }
            }

            //verificar se no banco de dados existe o produto que será excluido
            $results = $db->EXE_QUERY($sql_search, $param);

            //Se o produto existir 
            if(count($results) == 1)
            {
                //Excluindo registro
                $results = $db->EXE_QUERY($sql_delete, $param);

                $data = [   'status'=>'Success',
                            'message' => $message,
                            'results'=>[]
                        ];
            }
            else
            {
                $message_error = "Produto inexistente no sistema";
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
            }
        }
        else
        {
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
        }

        return $data;
    }

    /**
     * Endpoint de alteração de um produto
     * Parâmetros da requisição:
     * id(obrigatório)
     * produto(obrigatório)
     * quantidade(obrigatório)
     *
     * @return void
     */
    public function update_product()
    {
        //parametros necessários para execução do método
        $parametros = [
            ['value'=>'id','type'=>'int'],
            ['value'=>'produto','type'=>'String'],
            ['value'=>'quantidade','type'=>'int']
            ];
    
            //Validando parametros recebidos pela API
            $message_error = $this->validate_params($parametros);
    
            //Verificando se houve erro na passagem de parametros
            if($message_error == '')
            {
                $param = [
                    ':id'=>$this->params['id'],
                ];

                $db = new database();

                //query de consulta da existencia do produto
                $sql_search = "SELECT id_produto FROM produtos 
                WHERE id_produto=:id AND deleted_at IS NULL";

                $results = $db->EXE_QUERY($sql_search, $param);

                //Se o produto existir 
                if(count($results) == 1)
                {
                    $param = [
                        ':id'=>$this->params['id'],
                        ':produto'=>$this->params['produto']
                    ];

                    //query de verificação da existencia de um produto com o nome que será alterado
                    $sql_search_product = "SELECT id_produto FROM produtos
                    WHERE produto =:produto AND id_produto!=:id AND deleted_at IS NULL";
    
                    $results = $db->EXE_QUERY($sql_search_product, $param);
                    if(count($results)==0)
                    {
                        $param = [
                            ':id'=>$this->params['id'],
                            ':produto'=>$this->params['produto'],
                            ':quantidade'=>$this->params['quantidade'] 
                        ];

                        //query de atualização do produto
                        $sql_update = "UPDATE PRODUTOS SET produto =:produto,
                         quantidade=:quantidade, updated_at = now()
                        WHERE id_produto=:id AND deleted_at IS NULL";
        
                        $results = $db->EXE_QUERY($sql_update, $param);
        
                        $data = [   'status'=>'Success',
                        'message' => 'Produto atualizado com sucesso',
                        'results'=>[]
                        ];
                    }
                    else
                    {
                        $data = ['status'=>'Error',
                        'message' => 'Você esta alterando um produto para outro ja existente',
                        'results'=>[]
                        ];
                    }

                }
                else
                {
                    $data = [   'status'=>'Error',
                    'message' => 'Produto inexistente no sistema',
                    'results'=>[]
                    ];
                }

            }
            else
            {
                $data = [   'status'=>'Error',
                'message' => $message_error,
                'results'=>[]
                ];
            }

        return $data;
    }

}