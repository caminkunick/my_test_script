function jwt_encode($data,$alg="sha256"){
  $header = json_encode(array("typ"=>"JWT","alg"=>$alg),JSON_UNESCAPED_UNICODE);
  $header64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        
  $payload = json_encode($data,JSON_UNESCAPED_UNICODE);
  $payload64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
  $signature = hash_hmac($alg,"{$header}.{$payload}","YOUR_SECRET_CODE");
  $signature64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
  return "{$header64}.{$payload64}.{$signature64}";
}
function jwt_decode($jwt){
  $split = explode(".",$jwt);
        
  $header = base64_decode($split[0]);
  $payload = base64_decode($split[1]);
  $signature = base64_decode($split[2]);
        
  $alg = !!json_decode($header,true) ? json_decode($header,true)["alg"] : null;
  if(!!$alg==false){ return null; }
        
  $signature_verify = hash_hmac($alg,"{$header}.{$payload}","YOUR_SECRET_CODE");
  if($signature!==$signature_verify){ return null; }
        
  return json_decode($payload,true);
}
