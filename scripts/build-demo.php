<?php
// Export only the two presentation screens and their public style/image assets.
$root = dirname(__DIR__);
$out = $root.'/demo-dist';
if (!is_dir($out)) mkdir($out);
ob_start();
require $root.'/Dashboard/dashboard.php';
$html = ob_get_clean();
$doc = new DOMDocument();
@$doc->loadHTML('<?xml encoding="UTF-8">'.$html);
$xpath = new DOMXPath($doc);
$form = $doc->getElementsByTagName('form')->item(0);
$form->setAttribute('id','book-form');
$submit = $form->getElementsByTagName('button')->item(0);
$submit->setAttribute('disabled','disabled');
$submit->textContent = 'Ajouter au catalogue de démonstration';
$inputs = $form->getElementsByTagName('input');
$inputs->item(0)->setAttribute('name','title');
$inputs->item(1)->setAttribute('name','author');
$panel = $xpath->query('//div[contains(@class,"top-sales")]')->item(0);
$panel->textContent = '';
$panel->appendChild($doc->createElement('h2','Catalogue de démonstration'));
$panel->appendChild($doc->createElement('p','Les ajouts restent dans cet onglet.'));
$list = $doc->createElement('ul');
$list->setAttribute('id','books');
$list->setAttribute('aria-live','polite');
$panel->appendChild($list);
$script = $doc->createElement('script');
$script->setAttribute('src','/catalogue.js');
$doc->getElementsByTagName('body')->item(0)->appendChild($script);
file_put_contents("$out/dashboard.html",str_replace('<?xml encoding="UTF-8">','',$doc->saveHTML()));
copy(__DIR__.'/catalogue.js',"$out/catalogue.js");
$home = str_replace(['Services/Login/login.php','Commandez maintenant'],['/dashboard','Ouvrir la démo bibliothèque'],file_get_contents($root.'/index.php'));
file_put_contents("$out/index.html",$home);
foreach (['Images','src/css'] as $relative) {
    $dest = "$out/Assest/$relative";
    if (!is_dir($dest)) mkdir($dest,0777,true);
    foreach (glob("$root/Assest/$relative/*") as $file) if (is_file($file)) copy($file,$dest.'/'.basename($file));
}
