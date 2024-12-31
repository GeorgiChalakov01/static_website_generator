<?php


function refresh_db($website_name, $tables, $languages, $phrases) {
	$db = new SQLite3("generated/$website_name/db.sqlite", SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
	$db->enableExceptions(true);

	foreach($tables as $table)
		$db->query($table);

	$db->exec("BEGIN");

	$db->query("DELETE FROM 'LANGUAGES'");
	$db->query("DELETE FROM 'PHRASES'");

	foreach($languages as $language)
		$db->query("INSERT INTO 'LANGUAGES' ('ISO_CODE', 'NAME') VALUES ('{$language['ISO_CODE']}', '{$language['NAME']}')");

	foreach($phrases as $phrase) 
		foreach($phrase['TRANSLATIONS'] as $translation)
			$db->query("INSERT INTO 'PHRASES' ('LANGUAGE_ISO_CODE', 'KEY', 'VALUE') VALUES ('{$translation['LANGUAGE_ISO_CODE']}', '{$phrase['KEY']}', '{$translation['VALUE']}')");

	$db->exec("COMMIT");

}

function index_top($client_name) {
	echo "<?php
session_start();
require_once('functions.php');


	";

	#IMPORTANT MAKE THE LANGUAGES BELOW DYNAMIC, COMING FROM THE DB!
	echo "
# DETECT LANGUAGE FROM THE CLIENT'S IP

# US
#\$ip = '52.25.109.230';
# AFG
#\$ip = '180.94.77.212';

\$ip = getVisIPAddr();
\$ipdat = @json_decode(file_get_contents(
    'http://www.geoplugin.net/json.gp?ip=' . \$ip));

if(isset(\$_SESSION['language']) == false)
if(\$ipdat->geoplugin_countryName == 'Bulgaria')
	\$_SESSION['language']='BG';
else if(\$ipdat->geoplugin_countryName == 'Afghanistan')
	\$_SESSION['language']='PS';
else
	\$_SESSION['language']='EN';
# echo 'Country Name: ' . \$ipdat->geoplugin_countryName . '<br>';
# echo 'City Name: ' . \$ipdat->geoplugin_city . '<br>';
# echo 'Continent Name: ' . \$ipdat->geoplugin_continentName . '<br>';
# echo 'Latitude: ' . \$ipdat->geoplugin_latitude . '<br>';
# echo 'Longitude: ' . \$ipdat->geoplugin_longitude . '<br>';
# echo 'Currency Symbol: ' . \$ipdat->geoplugin_currencySymbol . '<br>';
# echo 'Currency Code: ' . \$ipdat->geoplugin_currencyCode . '<br>';
# echo 'Timezone: ' . \$ipdat->geoplugin_timezone;


	";

	echo "
require_once('theme_preset.php');

	";

echo "
\$db = new SQLite3('db.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
\$db->enableExceptions(true);

\$phrases = get_phrases(\$db);
log_user_visit(\$db);
\$languages = get_languages(\$db);

\$db->close();
?>


	";

	# IMPORTANT MAKE ALL IMAGES WEBP FORMAT
	echo "
<!doctype html>
<html lang='<?php echo \$_SESSION['language']; ?>' data-bs-theme='<?php echo \$_SESSION['theme']; ?>'>
        <head>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <title><?php echo '$client_name';?></title>
                <link rel='icon' type='image/png' href='images/tab_logo.png'>
                <link rel='stylesheet' href='styles/global_styles.css'>
                <?php require_once('bootstrap_head.php');?>
        </head>
        <body>

	";
}

function index_bottom() {
	echo "

                <?php require_once('bootstrap_body.php');?>
        </body>
</html>
	";
}

function navbar($anchors, $languages) {
	echo "
<nav class='navbar fixed-top navbar-expand-lg bg-body-tertiary' style='transition: transform 0.3s;'>
	<div class='container-fluid'>
		<a class='navbar-brand' href='#'>
			<img src='images/logo.jpg' alt='logo' style='height: 44px; border-radius: 10px; border: 1px solid black;'>
		</a>
		<button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarScroll' aria-controls='navbarScroll' aria-expanded='false' aria-label='Toggle navigation'>
			<span class='navbar-toggler-icon'></span>
		</button>
		<div class='collapse navbar-collapse text-center' id='navbarScroll'>
			<ul class='navbar-nav ms-auto my-2 my-lg-0 navbar-nav-scroll' style='--bs-scroll-height: 100px;'>
	";

	# Anchors
	foreach($anchors as $anchor){
		if(isset($anchor['dropdown-anchors']) == false){
			echo "
				<li class='nav-item'>
					<a class='nav-link active' aria-current='page' href='{$anchor['href']}'><?php echo \$phrases['{$anchor['phrase-key']}']; ?></a>
				</li>
			";
		}
		else {
			echo "
				<li class='nav-item dropdown'>
					<a class='nav-link dropdown-toggle' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
						<?php echo \$phrases['{$anchor['phrase-key']}'];?>
					</a>
					<ul class='dropdown-menu'>
			";
			foreach($anchor['dropdown-anchors'] as $subanchor){
				echo"
						<li><a class='dropdown-item' href='{$subanchor['href']}'>
							<?php echo \$phrases['{$subanchor['phrase-key']}']; ?>
						</a></li>
				";
			}
			echo "
					</ul>
				</li>
			";
		}
	}

	# Languages
	echo "
				<li class='nav-item dropdown me-5'>
					<a class='nav-link dropdown-toggle' href='change_language.php?language=<?php echo \$_SESSION['language']; ?>' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
						<img src='images/languages/<?php echo \$_SESSION['language']; ?>.jpg' alt='<?php echo \$_SESSION['language']; ?>' style='height: 22px; width: 22px; border-radius: 10px;'>
					</a>
					<ul class='dropdown-menu'>
	";

	foreach($languages as $language){
		echo "
						<li><a class='dropdown-item' href='change_language.php?language={$language['ISO_CODE']}'>
							<img src='images/languages/{$language['ISO_CODE']}.jpg' alt='{$language['NAME']}' style='height: 22px; width: 22px; border-radius: 10px;'>
							{$language['NAME']}
						</a></li>
		";
	}

	echo "
					</ul>
				</li>
	";

	# Theme
	echo "
				<li class='nav-item' style='background-color: #cccccc; border-radius: 10px; width: fit-content; padding: 0px 9px 0px 9px;'>
					<a class='nav-link' href='change_theme.php'>
						<img src='https://static-00.iconduck.com/assets.00/dark-theme-icon-2048x2048-ymrfkxsy.png' alt='Change Theme' style='width: 22px; height: 22px;'>
					</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
<script type='text/javascript' src='scripts/hide_navbar.js'></script>
	";
}

function carousel($coursel_height_percent, $time_auto_next_ms, $slides,  $strip_heading_phrase_key) {
	echo "
<div id='carousel' class='carousel carousel-dark slide'>
	<div class='carousel-indicators'>
	";


	$slide_number = 0;
	foreach($slides as $slide){
		if($slide_number == 0) $class = 'class=active';
		else $class = '';
		echo "
		<button type='button' data-bs-target='#carousel' data-bs-slide-to='$slide_number' $class aria-current='true' aria-label='Slide $slide_number'></button>
		";
		$slide_number++;
	}
	echo "
	</div>
	<div class='carousel-inner'>
	";


	$slide_number = 0;
	$coursel_height = $coursel_height_percent . 'vh';
	foreach($slides as $slide){
		if($slide_number == 0) $active = 'active';
		else $active = '';
		echo "
		<div class='carousel-item $active' data-bs-interval='$time_auto_next_ms'>
			<div style='height: $coursel_height; overflow: hidden;'>
				<img src='{$slide['image_src']}' class='d-block w-100' style='height: 100%; width: 100%; object-fit: cover;' alt='{$slide['alt']}'>
			</div>
			<div class='carousel-caption d-none d-md-block ' style='background-color: white; opacity: 0.8; width: fit-content; margin: 0 auto; padding: 5px; border-radius: 10px;'>
				<h5><?php echo \$phrases['{$slide['phrase-key-heading']}']; ?></h5>
				<p><?php echo \$phrases['{$slide['phrase-key-paragraph']}']; ?></p>
			</div>
		</div>
		";
		$slide_number++;
	}
	echo "
	</div>
	<button class='carousel-control-prev' type='button' data-bs-target='#carousel' data-bs-slide='prev'>
		<div style='background-color: white; padding: 10px; padding-top: 15px; border-radius: 10px;'>
			<span class='carousel-control-prev-icon' aria-hidden='true'></span>
			<span class='visually-hidden'>Previous</span>
		</div>
	</button>
	<button class='carousel-control-next' type='button' data-bs-target='#carousel' data-bs-slide='next'>
		<div style='background-color: white; padding: 10px; padding-top: 15px; border-radius: 10px;'>
			<span class='carousel-control-next-icon' aria-hidden='true'></span>
			<span class='visually-hidden'>Next</span>
		</div>
	</button>
</div>
	";

	# Strip
	$strip_height_percent = 100 - $coursel_height_percent;
	$strip_height = $strip_height_percent . 'vh';
	echo "
<div class='container' id='about-us' style='height: $strip_height; display: flex; justify-content: center; align-items: center;'>
        <div class='row justify-content-center text-center'>
		<h1 class='col-12'><?php echo \$phrases['$strip_heading_phrase_key']; ?></h1>
        </div>
</div>
	";
}

function partners() {
	echo '
Unfinished...
<div class="container">
	<div class="row justify-content-center" style="height: 20vh;">
		<div class="col-2 h-100" style="background-color: grey; border-radius: 10px; margin-right: 10px;"></div>
		<div class="col-2 h-100" style="background-color: grey; border-radius: 10px; margin-right: 10px;"></div>
		<div class="col-2 h-100" style="background-color: grey; border-radius: 10px; margin-right: 10px;"></div>
		<div class="col-2 h-100" style="background-color: grey; border-radius: 10px; margin-right: 10px;"></div>
		<div class="col-2 h-100" style="background-color: grey; border-radius: 10px; margin-right: 10px;"></div>
	</div>
</div>
	';
}

function heading_top_paragraph_bottom($heading_phrase_key, $paragraph_phrase_key, $id) {
	echo "
<div class='container' id='$id'>
	<div class='row justify-content-center'>
		<h1 class='col-10 text-center'><?php echo \$phrases['$heading_phrase_key']; ?></h1>
	</div>
	<div class='row justify-content-center'>
		<p class='col-10 text-center'><?php echo \$phrases['$paragraph_phrase_key']; ?></p>
	</div>
</div>
	";
}

function interactive_map($x, $y, $width, $height) {
	echo "
<div class='container'>
	<div class='row justify-content-center mt-5'>
		<div class='col-10 p-0' style='border-radius: 10px; background-color: lightblue;'>
			<div style='border: 2px black solid; border-radius: 10px;'>
				<div class='d-block w-100' style='height: 100%; width: 100%;' id='svg-container'></div>
			</div>
		</div>
	</div>
</div>

<script type='text/javascript' src='scripts/map.js'></script>
<script>
	load_map($x, $y, $width, $height);
</script>
	";
}

function find_us($heading_phrase_key, $paragraph_phrase_key, $map_height, $business_name, $id) {
	$business_name = urlencode($business_name);
	echo "
<div class='container' id='$id'>
	<div class='row justify-content-center text-center m-2'>
		<h1 class='col-md-10'><?php echo \$phrases['$heading_phrase_key']; ?></h1>
	</div>
	<div class='row justify-content-center'>
		<p class='col-md-3'><?php echo \$phrases['$paragraph_phrase_key']; ?></p>
		<div class='col-md-7'>
			<iframe 
				width='100%' 
				height='$map_height' 
				frameborder='0' 
				scrolling='no' 
				marginheight='0' 
				marginwidth='0' 
				id='gmap_canvas' 
				src='https://maps.google.com/maps?
					hl=en&amp;
					q=%20+($business_name)&amp;
					t=k&amp;z=3&amp;ie=UTF8&amp;
					iwloc=B&amp;
					output=embed'>
			</iframe> 
		</div>
	</div>
</div>
	";
}

function delimiter($color) {
	echo "
<div class='mt-5 mb-5' style='background-color: $color; height: 10px;'></div>
	";
}

function cards($cards) {
	echo "
<style>
/* Add this CSS code to your stylesheet */

.card {
  height: 400px;
  background-color: #cccccc;
  border-radius: 10px;
  border: 1px solid black;
  transition: transform 0.2s ease-in-out;
}

.card img {
  height: 100%;
  width: 100%;
  object-fit: cover;
  border: 1px solid black;
}

.card:hover {
  transform: scale(1.05);
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
</style>
<div class='container' id='services'>
	<div class='row justify-content-center'>
";
	foreach($cards as $card){
		echo "
		<div class='col-md-5 p-0 m-3 card'>
			<div style='width: 100%; height: 60%; overflow: hidden; border-radius: 10px 10px 0px 0px;'>
				<img src='{$card['picture']}' class='d-block w-100' alt='{$card['alt']}'>
			</div>
			<div class='row' style='width: 100%; height: 40%; overflow-x: hidden; overflow-y: auto; display: flex; justify-content: center; align-items: center;'>
				<h4 class='text-center' style='color: black;'>
					<?php echo \$phrases['{$card['heading-phrase-key']}']; ?>
				</h4>
				<p class='text-center' style='color: black;'>
					<?php echo \$phrases['{$card['paragraph-phrase-key']}']; ?>
				</p>
			</div>
		</div>
		";
	}
	echo "
	</div>
</div>
	";
}

function form($heading_phrase_key, $paragraph_phrase_key, $inputs, $submit_phrase_key) {
	echo "
<div class='container p-5' style='border-radius: 10px; border: 1px solid #cccccc;' id='contacts'>
	<div class='row justify-content-center'>
		<h1 class='text-center'><?php echo \$phrases['$heading_phrase_key']; ?></h1>
		<p class='text-center mt-3'><?php echo \$phrases['$paragraph_phrase_key']; ?></p>
	</div>
	<div class='row justify-content-center'>
		<form class='row g-3' action='send_message.php' method='POST'>
	";
	foreach($inputs as $input){
		echo "
			<div class='col-md-{$input['col-md']}'>
				<label for='{$input['id']}' class='form-label'><?php echo \$phrases['{$input['phrase-key']}']; ?></label>
		";
		if($input['type'] != 'textarea'){
			echo "
				<input type='{$input['type']}' class='form-control' name='{$input['id']}' id='{$input['id']}'>
			";
		}
		else {
			echo "
				<textarea class='form-control' id='{$input['id']}' name='{$input['id']}' rows='3'></textarea>
			";
		}
		echo "
			</div>
		";
	}

	echo "
			<div class='col-12'>
				<button type='submit' class='btn col-12' style='background-color: #cccccc; color: black;'><?php echo \$phrases['$submit_phrase_key']; ?></button>
			</div>
		</form>
	</div>
</div>
	";
}

function footer($copyright_phrase_key, $links) {
	echo "
<br>
<br>
<br>
<br>
<div class='container'>
	<footer class='d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top'>
		<div class='col-md-4 d-flex align-items-center'>
			<a href='#' class='mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1'>
				<img src='images/logo.jpg' alt='logo' style='height: 44px; border-radius: 10px; border: 1px solid black;'>
			</a>
			<span class='mb-3 mb-md-0 text-body-secondary'><?php echo \$phrases['$copyright_phrase_key']; ?></span>
		</div>

		<ul class='nav col-md-4 justify-content-end list-unstyled d-flex'>
	";

	foreach($links as $link) {
		echo "
			<li class='ms-3'><a class='text-body-secondary' href='{$link['href']}' target='_blank'>
				<img src='{$link['image']}' alt='{$link['alt']}' class='rounded' style='height: 24px; border: 1px solid black;'>
			</a></li>
		";
	}

	echo "
		</ul>
	</footer>
</div>
	";
}
