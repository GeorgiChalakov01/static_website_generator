This is a static php website generator.
It is capable of creating a site based on the provided to it information.

Before you can generate a website you must do these things:
1. Tell the generator what components are going to be in your page. This is done through the page_constructor.php file in plane php.
1. Change the config files in config/ to suit your needs.
1. Generate the page. This is done with the generate.sh script. If you run it it will tell you how to specify the website name and page name. Note: you can create multiple pages one by one with the generate.sh script, you just have to specify the same website name.

After the generation of the website you can copy it to another location without hesitation.

Happy generation and don't forget to contribute to the development of the project.
When pushing changes to the repo be careful not to push your personal config and page_constructor files.
