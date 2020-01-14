<?php

$handle = fopen("listaemails.csv", "r");
$row = 0;
while ($line = fgetcsv($handle, 1000, ";")) {
	if ($row++ == 0) {
		continue;
	}
	
	$dados[] = [
		'nome' => $line[0],
		'email' => $line[1],
	];
}

fclose($handle);



$path = "arquivos/";

$diretorio = dir($path);

echo "Lista de Arquivos do Diretório '<strong>".$path."</strong>':<br />";

$list_arquivos = [];

while($arquivo = $diretorio -> read()){
    if($arquivo !== '.' && $arquivo !== '..'){
        $dado = explode('-', $arquivo);
        $list_arquivos[] = [
            'nome' => $dado[0],
            'arquivo' => $path.''.$arquivo,
            'enviado' => false,
        ];
        //echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";
    }
}

$arquivo = rtrim($arquivo, '.pdf');
$diretorio -> close();







function enviaArquivo($nome, $email, $arquivo){
    //echo  $nome. " - ".$email."<br />";

// Caminho da biblioteca PHPMailer
require 'vendor/autoload.php';

 // Instância do objeto PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
 // Configura para envio de e-mails usando SMTP
$mail->isSMTP();
 // Servidor SMTP
$mail->Host = 'mail.rialci.com.br';
 // Usar autenticação SMTP
$mail->SMTPAuth = true;
 // Usuário da conta
$mail->Username = 'assc@rialci.com.br';
 // Senha da conta
$mail->Password = '********';
 // Tipo de encriptação que será usado na conexão SMTP
$mail->SMTPSecure = 'ssl';
 // Porta do servidor SMTP
$mail->Port = 465;
 // Informa se vamos enviar mensagens usando HTML
$mail->IsHTML(true);
 // Email do Remetente
$mail->From = 'assc@rialci.com.br';
 // Nome do Remetente
$mail->FromName = 'ASSC | Associação de Saúde dos Servidores Civis';
 // Endereço do e-mail do destinatário
$mail->addAddress($email);
// Assunto do e-mail
$mail->Subject = 'Boleto - Plano de Saúde Hapvida | ASSC'; 
// Mensagem que vai no corpo do e-mail
$mail->Body = '<h1>Mensagem enviada via PHPMailer</h1>';
 // Envia o e-mail e captura o sucesso ou erro
if($mail->Send($nome, $email, $arquivo)):
    echo 'Enviado com sucesso !';
else:
    echo 'Erro ao enviar Email:' . $mail->ErrorInfo;
endif;



    // Códigos para envio do email
    // Estudar para saber como se envia email pelo PHP
    // Existe uma função chamada mail()

    return true;
}

foreach($dados as $d){
    foreach($list_arquivos as $key => $a){
        if($d['nome'] === $a['nome']){
            $list_arquivos[$key]['email'] = $d['email'];
            $list_arquivos[$key]['enviado'] = enviaArquivo($d['nome'], $d['email'], $a['arquivo']);
        }
    }
}

echo "<br />";
echo "<table border='1px'>";
echo "<thead><tr>";
echo "<td>Nome</td>";
echo "<td>Email</td>";
echo "<td>Arquivo</td>";
echo "<td>Confirmação de Envio?</td>";
echo "</tr></thead>";

echo "<tbody>";
foreach($list_arquivos as $a){
    echo "<tr>";
    echo "<td>".$a['nome']."</td>";
    echo "<td>".$a['email']."</td>";
    echo "<td>".$a['arquivo']."</td>";
    echo "<td>".($a['enviado'] ? 'Enviado' : 'Não Enviado')."</td>";
    echo "</tr>";
}
echo "</tbody>";

echo "</table>";

?>