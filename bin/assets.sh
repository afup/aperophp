#!/bin/bash
#
# Localisation
#
SCRIPTDIR=$(cd $(dirname "$0"); pwd)
MAINDIR=$(cd $(dirname "$DIR"); pwd)
BOOTSTRAPDIR=$MAINDIR/vendor/twitter/bootstrap
[ -d $MAINDIR/tmp ] || mkdir $MAINDIR/tmp
DATE=$(date +%I:%M%p)
CHECK="\\033[1;32m✔\\033[0;39m"
HR=\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#

echo "${HR}"
echo "Building Bootstrap..."
echo "${HR}"

#
# Intégration FontAwesome
#
cp $MAINDIR/vendor/FortAwesome/Font-Awesome/font/font* $MAINDIR/web/font/
cp $MAINDIR/vendor/FortAwesome/Font-Awesome/css/font-awesome-ie7.css $MAINDIR/web/css/
echo -e " - Integrate FontAwesome...                 ${CHECK} Done"

#
# Compilation Twitter Bootstrap
#
cp $BOOTSTRAPDIR/less/bootstrap.less $MAINDIR/tmp/
cp $BOOTSTRAPDIR/less/responsive.less $MAINDIR/tmp/
sed -i '' -e "s!import \"!import \"../vendor/twitter/bootstrap/less/!" -e 's!vendor/twitter/bootstrap/less/sprites.less";!vendor/FortAwesome/Font-Awesome/less/font-awesome.less";!' $MAINDIR/tmp/bootstrap.less
echo -e "@import \"../assets/apero.less\";" >> $MAINDIR/tmp/bootstrap.less
sed -i '' -e "s!import \"!import \"../vendor/twitter/bootstrap/less/!" $MAINDIR/tmp/responsive.less
echo -e "@import \"../assets/apero-responsive.less\";" >> $MAINDIR/tmp/responsive.less
echo -e " - Prepare Bootstrap less files...          ${CHECK} Done"
jshint $BOOTSTRAPDIR/js/*.js --config $BOOTSTRAPDIR/js/.jshintrc
echo -e " - Running JSHint on javascript...          ${CHECK} Done"
recess --compile $MAINDIR/tmp/bootstrap.less > $MAINDIR/web/css/bootstrap.css
recess --compress $MAINDIR/tmp/bootstrap.less > $MAINDIR/web/css/bootstrap.min.css
recess --compile $MAINDIR/tmp/responsive.less > $MAINDIR/web/css/bootstrap-responsive.css
recess --compress $MAINDIR/tmp/responsive.less > $MAINDIR/web/css/bootstrap-responsive.min.css
echo -e " - Compiling LESS with Recess...            ${CHECK} Done"
cat $BOOTSTRAPDIR/js/bootstrap-transition.js $BOOTSTRAPDIR/js/bootstrap-alert.js $BOOTSTRAPDIR/js/bootstrap-button.js $BOOTSTRAPDIR/js/bootstrap-carousel.js $BOOTSTRAPDIR/js/bootstrap-collapse.js $BOOTSTRAPDIR/js/bootstrap-dropdown.js $BOOTSTRAPDIR/js/bootstrap-modal.js $BOOTSTRAPDIR/js/bootstrap-tooltip.js $BOOTSTRAPDIR/js/bootstrap-popover.js $BOOTSTRAPDIR/js/bootstrap-scrollspy.js $BOOTSTRAPDIR/js/bootstrap-tab.js $BOOTSTRAPDIR/js/bootstrap-typeahead.js > $MAINDIR/tmp/bootstrap.js
uglifyjs -nc $MAINDIR/tmp/bootstrap.js > $MAINDIR/tmp/bootstrap.min.tmp.js
echo -e "/*!\n* Bootstrap.js by @fat & @mdo\n* Copyright 2012 Twitter, Inc.\n* http://www.apache.org/licenses/LICENSE-2.0.txt\n*/" > $MAINDIR/tmp/copyright.js
cat $MAINDIR/tmp/copyright.js $MAINDIR/tmp/bootstrap.min.tmp.js > $MAINDIR/web/js/bootstrap.min.js
echo -e " - Compiling and minifying javascript...    ${CHECK} Done"
#rm -Rf $MAINDIR/tmp
echo -e " - Cleaning...                              ${CHECK} Done"

echo "${HR}"
echo "Bootstrap for AperoPHP successfully built at ${DATE}"
echo "${HR}"
