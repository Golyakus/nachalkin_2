	DEPLOYMENT INSTRUCTIONS
1. git clone repository-name
2. composer install
3. edit web/index.php and comment two lines with DEVELOPMENT and DEBUG defines for PROD environment (for DEV - uncomment)
4. cd config
5. cp environ-samples/db-xxx.php db.php
currently db-dev and db-prod files are stored

