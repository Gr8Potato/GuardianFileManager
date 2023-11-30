```md
# Guardian File Manager (GFM)
This was a little project I put together for my LSU Intro to Cybersecurity class. You can access the GFM at https://guardianfilemanager.duckdns.org.

## Features
1. Account Registration
   - Guardians can create their own GFM account which gives them their own sanctuary to store files.
2. Shared Sanctuary
   - Guardians can share a sanctuary with other guardians, allowing them to send and receive files with one another. Elder Guardians can choose how much power a guardian has on the Shared Sanctuary.
3. File Previewing
   - Guardians can preview txt, pdf, and most image file formats.
4. Sanctuary Modification
   - Guardians can upload, download, and delete uploaded content from their own sanctuary. With sufficient permissions, guardians can modify the Shared Sanctuary.
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
- OpenLDAP (Linux implementation of LDAP)
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

## Frameworks
No frameworks were used to make this project. I wanted to sit down and see how a project could be done "the old-fashioned way." I intend to pick up some more frameworks to see how they affect the ease of work as a developer for future projects.

### Architecture
[This was a rough mental map I had in the back of my mind when making this project.](https://imgur.com/a/X1NhrFh) Specific technologies and modules were decided along the way.
```
