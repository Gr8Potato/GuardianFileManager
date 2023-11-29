# Guardian File Manager (GFM)
This was a little project I put together for my LSU Intro to Cybersecurity class. You can access the GFM at https://guardianfilemanager.duckdns.org.

## Features
1. Account Registration
   - Guardians can create their own GFM account which gives them their own sanctuary to store files.
2. Shared Sanctuaries
   - Guardians can share sanctuaries with other guardians, allowing them to send and receive files with one another.
3. File Previewing
   - Guardians can preview txt, pdf, and most image file formats.
4. Sanctuary Modification
   - Guardians can upload, download, and delete uploaded contents from their own sanctuary. With sufficient permissions, guardians can modify shared sanctuaries.
5. Batch File Uploading

## QoL
1. Audit logging
   - All guardian activity is monitored for enhanced security.
2. HTTPS redirects
   - Alongside the implementation of HTTPS, all HTTP requests will be redirected to encrypted pipes.
3. Web serving obfuscation
   - The user can't see the type of web page being requested. This is to enhance security and serves as a QoL feature.
4. DNS
   - A DNS provider was used because the alternative isn't fun.
5. Password hashing
   - All passwords are stored in a salted & hashed form. 

## Technologies
- Apache2
- OpenLDAP (implmentation of ldap)
- OpenSSL
- Amazon Elastic Compute Cloud (EC2)
- Duck DNS
- Let's Encrypt
- Wireshark (testing)

## Languages
- HTML
- CSS
- JavaScript
- PHP

### Architecture
[This was a rough mental map I had in the back of my mind when making this project.](https://imgur.com/a/X1NhrFh) Specific technologies and modules were decided along the way.
