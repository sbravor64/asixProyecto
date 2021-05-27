use `wordpress`;
UPDATE wp_options SET option_value = 'miweb'
                WHERE option_name = 'blogname';
UPDATE wp_users SET user_login = 'admin',
                user_pass = MD5('admin1234'), user_email = 'admin@gmail.com', 
                user_url = 'midom.proyecto.ccff.site:8080'
                WHERE ID = 1;
