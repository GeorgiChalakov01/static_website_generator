#!/bin/sh

generator_root=$(pwd)

if [ $# -ne 2 ]; then
	echo "Wrong use of the generator. "
	echo "Usage: ./generate.sh website_name generated_page_name"
	echo "Example: ./generate.sh my_website index.php"
	exit 1
fi

echo "Checking if the folder generated exists."
if [ -d "generated" ]; then
	echo "The folder generated already exists."
else
	echo "Creating the folder generated."
	mkdir $generator_root/generated
fi

echo "Checking the status of $1."
if [ -d "generated/$1" ]; then
	echo "$1 already exists, skipping foundational files."
else
	echo "$1 doesn't exists, copying foundational files."
	cp -r $generator_root/resources $generator_root/generated/$1
	cp $generator_root/config/config.php $generator_root/generated/$1
fi

echo "Generating $2"
php $generator_root/page_constructor.php $1 > $generator_root/generated/$1/$2

echo "Page $2 generated in: generated/$1/"

echo "Starting Prevew."

echo "Installing dependencies."
cd $generator_root/generated/$1
./install_dependencies.sh

echo "Starting PHP development server."
cd $generator_root/generated/$1
php -S localhost:8080 &

echo "Opening Browser"
firefox --new-window localhost:8080/$2
