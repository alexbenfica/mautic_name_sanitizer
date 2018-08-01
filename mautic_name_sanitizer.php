<?php


# http://stackoverflow.com/questions/2517947/ucfirst-function-for-multibyte-character-encodings
function mb_ucfirst($name, $encoding){
    $size = mb_strlen($name, $encoding);
    $firstChar = mb_substr($name, 0, 1, $encoding);
    $then = mb_substr($name, 1, $size - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}


# Put the correct case on names.
# Useful to sanitize names on contact emails and newsletters.
# Also remove extraneous characters from names
function nameCase($name){

    # Avoid any strange character on names
    $name = trim($name,"'\/0123456789()*%$|~#?!^][}{;/\\<>,");

    # It is aimed to Brazilian names... but you can include you own exceptions bellow!
    $word_splitters = array(' ', '-', "O'", "L'", "D'", 'St.', 'Mc', 'Dr', 'Dra', 'Sr', 'Sra');
    $lowercase_exceptions = array('the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'das', 'of', 'and', "l'", "d'", 'do', 'dos', 'no', 'nas', 'nos', 'ou');
    $uppercase_exceptions = array('III', 'IV', 'VI', 'VII', 'VIII', 'IX');

    $name = mb_strtolower($name, 'UTF-8');

    foreach ($word_splitters as $delimiter) {
        $words = explode($delimiter, $name);
        $newwords = array();
        foreach ($words as $word) {
            if (in_array(strtoupper($word), $uppercase_exceptions))
                $word = strtoupper($word);
            else
                if (!in_array($word, $lowercase_exceptions))
                    $word = mb_ucfirst($word, 'utf-8');

            $newwords[] = $word;
        }
        if (in_array(strtolower($delimiter), $lowercase_exceptions))
            $delimiter = strtolower($delimiter);

        $name = join($delimiter, $newwords);
    }
    return trim($name);
}



# get Mautic database information from config.ini file from the same folder.
$db = parse_ini_file('config.ini','db')['db'];


# connect to Mautic database
$con = @mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}
$con->set_charset ('utf8');




# get all names
$sql = 'select id,firstname from ma_leads where firstname is not null limit 99999';
$query 	= mysqli_query($con, $sql);

while ($row = mysqli_fetch_array($query)){

    #print("0: $name\n");

    $name = $row['firstname'];
    $fixed_name = nameCase($name);


    if($name != $fixed_name){

        $escaped_fixed_name = $con->real_escape_string($fixed_name);
        $id = $row['id'];
        $sql = "update ma_leads set firstname = '".$escaped_fixed_name."' where id = $id";

        mysqli_query($con, $sql);

        # simple output or debug information...
        print("0: $name");
        echo "\n";
        print("1: ".$fixed_name);
        echo "\n";
        print($sql);
        echo "\n";
        echo "\n";

    }



}



// Close connection
mysqli_close ($con);
