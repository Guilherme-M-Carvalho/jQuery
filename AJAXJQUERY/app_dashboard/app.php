<?php

class Dashboard{
    public $data_inicio;
    public $data_fim;
    public $numero_vendas;
    public $totalVendas;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
        return $this;
    }
}

class Conexao{
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $password = 'root';

    public function conectar(){
        try{
            $conexao = new PDO("mysql:host=$this->host;dbname=$this->dbname", "$this->user","$this->password");

            $conexao->exec('set charset set utf8');

            return $conexao;
        } catch (PDOExection $e){
            echo $e;
        }
    }
}

class Bd{
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard){
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function getNumVendas(){
        $query = "select count(*) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_fim";

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }
    public function getTotalVendas(){
        $query = "select sum(total) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_fim";

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }
}

$dashboard = new Dashboard();
$conexao = new Conexao();
$bd = new Bd($conexao, $dashboard);

$competencia = explode("-",$_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


$dashboard->__set('data_inicio', $ano. '-'.$mes .'-01');
$dashboard->__set('data_fim', $ano. '-'.$mes .'-'.$dias_do_mes);
$dashboard->__set('numero_vendas', $bd->getNumVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
echo json_encode($dashboard);

?>