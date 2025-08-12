Tento malý PHP script umí automaticky vkládat do obrázkù Vae logo. Ve probíhá bìhem zobrazování. Tzn. obrázky jsou na vaem webu uloeny bez loga a tento script logo pøidá a pøi naètení do prohlíeèe. Pùvodní obrázky (bez loga) jsou pro normálního uivatele schovány.

Tento script je uloen v jednom jediném souboru, který se mùe jmenovat jak Vás jen napadne. Díky tomu, mùete mít na svém webu nìkolik kopií tohoto scriptu a kadý mùe mít vlastní nastavení.

Jak volat tento script?

Snadno:
http://www.domena.cz/jmenoscriptu.php?img=obrazek
Kde obrazek je jméno souboru.
obrazek ale neobsahuje plnou cestu k obrázku, ale jen její poslední èást. V samotném scriptu je nutné nastavit cestu k adresáøi s obrázky. (mùe zùstat ale i prázdná). Skuteèná cesta je pak následující: image_path + obrazek

Nastavení scriptu:

Na zaèátku PHP kódu je nìkolik promìnných, které je nutné nastavit...

$image_quality - urèuje kvalitu generovaného JPEG obrázku.
Hodnota je èíslo od 0 do 100, kde 100 je nejlepí.

$image_path - cesta k adresáøi s obrázky
ukázka: "./" - vechny obrázky jsou ve stejném adresáøi jako script.
"./images/" - obrázky jsou v adresáøi, který se jmenuje images

$logo_path - Cesta a název souboru s logem. Logo mùe být JPEG, GIF nebo PNG.
ukázka: "./logo.gif"

$logo_pos_x - Horizontální poloha loga.
Mùe být: left, right or center

$logo_pos_y - Vertikální poloha loga.
Mùe být: top, middle or bottom

$error_not_found - text, který bude zobrazen, kdy se nepovede nalézt obrázek.

$error_not_supported - text, který bude zobrazen, kdy je obrázek v nepodporovaném formátu.

$error_bg_color a $error_text_color jsou barvy pozadí a textu pro chybové zprávy
Tyto hodnoty musí být typu ARRAY, proto pouijte PHP funkci array() - array(RRR,BBB,GGG)
Kde RRR je èervená, BBB je modrá a GGG je zelená. Vechny hodnoty musí být v rozsahu 0 a 255 