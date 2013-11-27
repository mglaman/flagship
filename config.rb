require 'compass-normalize'
require 'toolkit'
require 'susy'
require 'sass-globbing'
# Require any additional compass plugins here.


# Set this to the root of your project when deployed:
css_dir = "css"
sass_dir = "sass"
images_dir = "images"
javascripts_dir = "js"

output_style = :nested
# environment = :development
environment = :production

relative_assets = true
additional_import_paths = ["sass"]
sass_options = {:debug_info=>false}

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false
# line_comments = true


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass
