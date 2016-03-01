<?php 

$inGlobal = "";
$outGlobal = "";

function achaInclude ($linha) {
	if (strpos($linha,"include_once ") !== FALSE || strpos($linha,"include ") !== FALSE || strpos($linha,"require_once ") !== FALSE || strpos($linha,"require ") !== FALSE){
		$pattern = "@\"(.*)\"@i";
		preg_match($pattern, $linha, $matches);
		if (!$matches) {
			$pattern = "@'(.*)'@i";
			preg_match($pattern, $linha, $matches);
		}
		if (!$matches)
			return FALSE;
		return $matches[count($matches) -1];
	}
	return FALSE;
}
/* Gera arquivo resumido
 * string $in arquivo de entrada
 * string $out arquivo de saída. NULL significa que a saída será retornada na função ao invez de salva em arquivo
 */
function minArquivo($in, $out = NULL) {
	global $inGlobal, $outGlobal;
	
	$lines = file($in);

	if (!$lines) 
		throw new Exception ("Não foi possivel encontrar o arquivo $filename");
	
	$file = NULL;
	if ($out) {
		$inGlobal = $in;
		$outGlobal = $out;
		$file = fopen("$out", 'w');
		if (!$file)
			throw new Exception ("Falha ao abrir o arquivo $out");
	}
			
	if (!$out && strpos($in,".php") ){
		for ($i = 0; $i < count($lines); $i++) {
			$lines[$i] = trim($lines[$i]);
			if (strlen($lines[$i]) == 0 )
				continue;
			if (preg_match('/<\?php/',$lines[$i])) {
				$lines[$i] = '';
				break;
			}
		}
	}
	
	$string = "";
	
	/* Retira os comentários e linhas vazias e cria uma unica linha */
	foreach ($lines as $line) {
		$line = trim($line);
		if (strlen($line) > 0 && !preg_match('/^\/\//', $line)) {
			$line = str_replace($inGlobal, $outGlobal, $line);
			$include = achaInclude($line);
			if ($include)
				$string .= minArquivo($include);
			else
				$string.= $line . " ";
		}
	}

	if ($out) {
		if (fwrite($file, $string) === FALSE)
			throw new Exception("Falha ao escrever no arquivo $out");

		if (!fclose($file))
			throw new Exception ("Falha ao fechar o arquivo $out");
		echo "<h1>Arquivo $out criado!</h1>";
	}
	else
		return $string;
}

minArquivo("index.php", "criador.php");
//minArquivo("DataBaseConfig.php", "criador.php");