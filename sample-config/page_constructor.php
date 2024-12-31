<?php
require_once("config.php");
require_once("config_languages.php");
require_once("config_db_tables.php");
require_once("{$argv[2]}/components.php");


refresh_db($argv[1], $tables, $languages, $phrases);

index_top($business_name);

$anchors = [
	[
		'phrase-key' => 'navbar-home',
		'href' => '#'
	],
	[
		'phrase-key' => 'navbar-services',
		'href' => '#services',
		'dropdown-anchors' => [
			[
				'phrase-key' => 'navbar-services-transportation',
				'href' => '#services'
			],
			[
				'phrase-key' => 'navbar-services-vehicle',
				'href' => '#services'
			],
		]
	],
	[
		'phrase-key' => 'navbar-about-us',
		'href' => '#about-us'
	],
	[
		'phrase-key' => 'navbar-find-us',
		'href' => '#find-us'
	],
	[
		'phrase-key' => 'navbar-contacts',
		'href' => '#contacts'
	]
];

navbar(
	$anchors,
	$languages
);


$coursel_height_percent = '80';
$time_auto_next_ms = 2000;
$slides = [
	[
		'image_src' => 'https://images.alphacoders.com/504/504216.jpg',
		'alt' => 'Image of a truck',
		'phrase-key-heading' => 'label-slide1-heading',
		'phrase-key-paragraph' => 'label-slide1-paragraph'
	],
	[
		'image_src' => 'https://images.alphacoders.com/504/504216.jpg',
		'alt' => 'Image of a truck',
		'phrase-key-heading' => 'label-slide2-heading',
		'phrase-key-paragraph' => 'label-slide2-paragraph'
	],
	[
		'image_src' => 'https://images.alphacoders.com/504/504216.jpg',
		'alt' => 'Image of a truck',
		'phrase-key-heading' => 'label-slide3-heading',
		'phrase-key-paragraph' => 'label-slide3-paragraph'
	]
];
$strip_heading_phrase_key = 'strip-heading';
carousel(
	$coursel_height_percent,
	$time_auto_next_ms,
	$slides,
	$strip_heading_phrase_key
);


$heading_phrase_key = 'empty';
$paragraph_phrase_key = 'heading-top-paragraph-bottom-paragraph';
$id = 'about-us';
heading_top_paragraph_bottom(
	$heading_phrase_key,
	$paragraph_phrase_key,
	$id
);


$x = 500;
$y = 180;
$width = 200;
$height = 220;
interactive_map(
	$x,
	$y,
	$width,
	$height
);


$color = '#cccccc';
delimiter($color);


$heading_phrase_key = 'find-us-heading';
$paragraph_phrase_key = 'find-us-paragraph';
$map_height = 400;
$id = 'find-us';
find_us(
	$heading_phrase_key,
	$paragraph_phrase_key,
	$map_height,
	$business_name,
	$id
);

delimiter($color);

$heading_phrase_key = 'services-heading';
$paragraph_phrase_key = 'services-paragraph';
$id = 'services';
heading_top_paragraph_bottom(
	$heading_phrase_key,
	$paragraph_phrase_key,
	$id

);


$cards = [
	[
		'picture' => 'https://images.alphacoders.com/504/504216.jpg',
		'alt' => 'Car',
		'heading-phrase-key' => 'card1-heading',
		'paragraph-phrase-key' => 'card1-paragraph'
	],
	[
		'picture' => 'https://news-site-za.s3.af-south-1.amazonaws.com/images/2024/09/FordMustang2024_003.jpg',
		'alt' => 'Car',
		'heading-phrase-key' => 'card2-heading',
		'paragraph-phrase-key' => 'card2-paragraph'
	]
];
cards(
	$cards
);


delimiter($color);


$heading_phrase_key = 'form-heading';
$paragraph_phrase_key = 'form-paragraph';
$inputs = [
	[
		'col-md' => '12',
		'phrase-key' => 'form-label-company',
		'id' => 'company',
		'type' => 'text'
	],
	[
		'col-md' => '6',
		'phrase-key' => 'form-label-first-name',
		'id' => 'first_name',
		'type' => 'text'
	],
	[
		'col-md' => '6',
		'phrase-key' => 'form-label-last-name',
		'id' => 'last_name',
		'type' => 'text'
	],
	[
		'col-md' => '6',
		'phrase-key' => 'form-label-email',
		'id' => 'email',
		'type' => 'email'
	],
	[
		'col-md' => '6',
		'phrase-key' => 'form-label-phone',
		'id' => 'phone',
		'type' => 'text'
	],
	[
		'col-md' => '12',
		'phrase-key' => 'form-label-message',
		'id' => 'message',
		'type' => 'textarea'
	],
];
$submit_phrase_key = 'form-submit';
form(
	$heading_phrase_key,
	$paragraph_phrase_key,
	$inputs,
	$submit_phrase_key
);


$copyright_phrase_key = 'footer-copyright';
$links = [
	[
		'href' => 'https://www.jobs.bg/',
		'image' => 'images/jobs.png',
		'alt' => 'Jobs BG'
	],
	[
		'href' => 'https://www.mobile.bg/',
		'image' => 'images/mobile.jpg',
		'alt' => 'Mobile BG'
	]
];
footer(
	$copyright_phrase_key,
	$links
);


index_bottom();
