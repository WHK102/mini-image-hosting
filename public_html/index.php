<?php

// Default config
$sys = array(
    'db'                =>  '.ht_dbimages', // Database
    'self_script'       =>  (isset($_SERVER['HTTPS']) == true ? 'https' : 'http' . '://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']),
    'start'             =>  0, // Bite start
    'length'            =>  0, // Bites length
    'extension'         =>  '.dat', // File extension
    'max_length'        =>  2000000, // Max upload/download file (2MB)
    'finish_poiner'     =>  0,
    'finish_length'     =>  0,
    'finish_extension'  =>  'jpg',
    'totalsizedb'       =>  0,
    'err'               =>  false,
    'headerdb'          =>  "DBIMG\x00\x01"
);

// Re-config...
if(!file_exists($sys['db']))
{
    file_put_contents($sys['db'], $sys['headerdb']);
}
$sys['totalsizedb'] = filesize($sys['db']);

if($_FILES)
{
    // Manage errors
    if((int)$_FILES['img']['size'] > (int)$sys['max_length'])
    {
        $sys['err'] = 'The file is too large.';
    }
    else if(!@exif_imagetype($_FILES['img']['tmp_name']))
    {
        $sys['err'] = 'The file is not an image.';
    }
    else
    {
        // Save file
        $sys['finish_pointer']      = (int)$sys['totalsizedb'];
        $sys['finish_length']       = filesize($_FILES['img']['tmp_name']);
        $sys['finish_extension']    = explode('.', $_FILES['img']['name']);
        $sys['finish_extension']    = substr($sys['finish_extension'][count($sys['finish_extension']) - 1], 0, 5);
        file_put_contents($sys['db'], file_get_contents($_FILES['img']['tmp_name']), FILE_APPEND | LOCK_EX);
    }
}
else if(isset($_GET['data']) and (strlen($_GET['data']) > 0))
{
    // Split data 
    $data = $_GET['data'];
    if(str_replace('/', '', $data) != $data)
    {
        $data = explode('/', $data);
        $data = $data[count($data) - 1];
    }

    $data = explode('_', $data);
    $sys['start'] = (int)$data[0];
    $data = explode('.', $data[1]);
    $sys['length'] = (int)$data[0];
    $sys['extension'] = $data[count($data) - 1];
    
    // Manage errors
    if($sys['length'] > $sys['max_length'])
    {
        $sys['err'] = 'The file is too large.';
    }
    else if($sys['start'] < 1)
    {
        $sys['err'] = 'The file does not exists.';
    }
    else if(($sys['start'] + $sys['length']) > $sys['totalsizedb'])
    {
        $sys['err'] = 'The file does not exists.';
    }
    else
    {
        $tmpf = tempnam(0, '');
        file_put_contents($tmpf, file_get_contents($sys['db'], NULL, NULL, $sys['start'], $sys['length']));
        if(!exif_imagetype($tmpf))
        {
            $sys['err'] = 'The file is not an image.';
        }
        else
        {
            // Get file
            header('Content-Type: image/'.substr($sys['extension'], 0, 5));
            header('Content-Length: '.(int)$sys['length']);
            
            // MAX Cache
            header('Last-Modified: Tue, 03 Jul 2001 06:00:00 GMT');
            header('Expires: Tue, 03 Jul 2500 06:00:00 GMT');

            echo file_get_contents($tmpf);
            exit; // End buffer
        }
    }
}
?>

<?php if($sys['err']){ ?>
    Error: <?php echo $sys['err']; ?><hr />
<?php }elseif((int)$sys['finish_pointer'] > 0){ ?>
    File saved: 
    <a target="_blank" href="<?php echo ($outfile = dirname($sys['self_script']).'/'.(int)$sys['finish_pointer'].'_'.(int)$sys['finish_length'].'.'.$sys['finish_extension']); ?>">
        <?php echo $outfile; ?>
    </a><hr />
<?php } ?>
<form action="<?php echo $sys['self_script']; ?>" method="post" enctype="multipart/form-data">
    Image: <input type="file" name="img" /> <input type="submit" value="Upload" />
</form>
