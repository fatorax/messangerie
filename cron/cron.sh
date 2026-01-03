#!/bin/bash
# Cron job pour supprimer les comptes de démonstration expirés

cd /home/u628601380/domains/messangerie.fatorax.fr/public_html

/usr/bin/php artisan app:delete-demo-account >> /dev/null 2>&1
