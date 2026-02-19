./vendor/bin/sail pint



http://localhost:9101/browser/local
sail
password
create "local" bucket (must match AWS_BUCKET)


./vendor/bin/sail artisan config:clear && sail artisan cache:clear && sail artisan view:clear


./vendor/bin/sail up -d


/vendor/bin/sail artisan route:list