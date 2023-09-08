<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperEase Transpiler</title>
</head>
<body>
<form method="POST" action="">
    <textarea name="text" id="text" style="width: 90vw; height:90vh;" placeholder="Enter HyperEase code...">
        //wat is tijd?
        page{
            header{
                h1{
                    "Welcome to HyperEase"
                h1}
            header}

            main{
                article{
                    h2{
                        "Introduction"
                    h2}

                    p{
                        "HyperEase is a revolutionary language for HTML development."
                    p}
                article}

                section{
                    h2{
                        "Features"
                    h2}

                    ul{
                        li(class="feature-item", data-id="1") {
                            a(href="https://example.com") {
                                "Simplicity"
                            a}
                        li}
                    ul}
                section}
            main}

            footer{
                p{
                    "Copyright © 2023. All rights reserved."
                p}
            footer}
        page}

        script{
            let a = 2;
            let b = 123;
            console.log(a * b)
        script}
    
    </textarea><br>
    <input type="submit" value="Submit">
</form>

<?php
if(isset($_POST['text'])){
    $hyperEaseCode = $_POST['text'];

    $htmlMarkup = transpiler($hyperEaseCode);

    echo "<h2>Generated HTML:</h2>";
    echo "<textarea style='width: 90vw; height: 90vh'>" . $htmlMarkup. "</textarea>";
}
?>

<?php
function transpiler($toBeCompiled)
{
    $html = '<!doctype html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    ';
    $inScript = false;


    $lines = explode("\n", $toBeCompiled);


    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;


        if(str_contains($line, "script}")){
            $inScript = false;
        }

        if($inScript){
            $html .= $line . "\n";
            continue;
        }
        if(str_contains($line, "script{")){
            $inScript = true;
        }

        if (str_contains($line, "page{")) {
            $html .= "<body>" . "\n";
            continue;
        }
        if (str_contains($line, "page}")) {
            $html .= "</body></html>" . "\n";
            continue;
        }

        if($line[0] == '"'){
            $line = str_replace('"', "", $line);
            $html .= $line . "\n";
            continue;
        }
        if(str_contains($line, "(")){
            $line = str_replace("(", " ", $line);
            $line = str_replace(")", " ", $line);
        }

        if (str_contains($line, "{")) {
            $lineToAdd = "<" . $line . ">";
            $lineToAdd = str_replace("{", "", $lineToAdd);
            $html .= $lineToAdd . "\n";
            continue;
        }

        if (str_contains($line, "}")) {
            $line = str_replace("}", "", $line);
            $line = "</" . $line . ">";
            $html .= $line . "\n";
            continue;
        }
    }

    file_put_contents("index.html", $html);
    header("Location: index.html");

    return $html;
}
?>
</body>
</html>
