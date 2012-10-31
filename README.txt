
CodeIgniter Web Application Setup on Linux Machine
==================================================

1. mod_rewrite should be enabled in Apache to allow rerouting of URLs using .htaccess file.
	- How to determine if enabled: 
		a. Try calling phpinfo() in any of your php file. Then look for apache2handler table, if it contains mod_rewrite.
		b. If there is no mod_rewrite, reconfigure your httpd.conf file to enable mod_rewrite.
			- The following should be in httpd.conf
			==============================================================
				LoadModule rewrite_module       modules/mod_rewrite.so
				
				<Directory "/usr/local/apache/htdocs/santa12">
					Options Indexes FollowSymLinks
					AllowOverride All
					Order allow,deny
					Allow from all
				</Directory>
            ==============================================================

			- Search for mod_rewrite.so in Linux machine by typing 'locate mod_rewrite.so'. If nothing could be found, search the net.
			
2. Configure your .htaccess file.
	- Location should be at the root folder of the application.
	
3. When visiting your site, the Linux machine may show error 'Disallowed Key Characters.'
	Try tweaking <webapp>/system/core/Input.php:
	Look for _clean_input_keys() function definition and change the following:
		From: "/^[a-z0-9:_\/-]+$/i"
		To:   "/^[a-z0-9:_\/\-\.]+$/i"
		
	Then try visiting your site again. If it still doesn't work, try tweaking <webapp>/application/config/config.php:
		From: $config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
		To: $config['permitted_uri_chars'] = 'a-z 0-9~%\.:_-';
