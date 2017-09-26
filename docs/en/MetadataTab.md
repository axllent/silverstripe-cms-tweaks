# Metadata Tab

The metadata tab (by default) moves the content from the metadata composite field to its own tab, and positions it to the right. Using a custom yml config (eg: `mysite/_config/metadatatab.yml`) You can turn this option off, modify its tab name, and turn off the "float right" option.

## Config

```yml
Axllent\CMSTweaks\MetadataTab:
  use_tab: true                 # default true
  tab_title: 'SEO'              # default `Advanced`
  tab_to_right: true            # default true
  page_name_title: 'Meta Title' # rename "Page name" to "Meta Title"
  move_title_to_advanced: true  # for users without canCreate() permissions, move Title to `Advanced` tab
```
