<?php

namespace Teelah\Prepositios;

class Preposicoes
{
    public function consultarPreposicoes(array $palavras)
    {
        // Pegando os valores das constantes
        $url = defined('CONF_API_PREPOSICOES_URL') ? CONF_API_PREPOSICOES_URL : null;
        $token = defined('CONF_API_PREPOSICOES_TOKEN') ? CONF_API_PREPOSICOES_TOKEN : null;
        $authorization = defined('CONF_API_PREPOSICOES_AUTHORIZATION') ? CONF_API_PREPOSICOES_AUTHORIZATION : null;
        
        // Verificando se as constantes estão definidas
        if (!$url || !$token || !$authorization) {
            return [
                'erro' => true,
                'mensagem' => 'As configurações necessárias não estão definidas.',
            ];
        }
        
        $tokenEncoded = base64_encode($token);
        
        $postFields = [
            'token' => $tokenEncoded,
            'sessao' => 'preposicoes',
            'palavras' => implode(',', $palavras)
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: $authorization " . base64_encode(defined('CONF_API_CLIENT_KEY') ? CONF_API_CLIENT_KEY : null)
        ]);    
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            return $response;
        } else {
            return [
                'erro' => true,
                'mensagem' => "Erro ao conectar ao webservice. Código HTTP: $httpCode",
                'detalhes' => $response
            ];
        }
    }
}


?>