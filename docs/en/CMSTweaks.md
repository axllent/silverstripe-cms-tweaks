# CMS Tweaks

CMSTweaks makes some minor alterations to the CMS, including the following:

## Hide help

It removed the default `CMS User help`, `Developer docs`, `Community` & `Feedback` links.

This can be disabled with

```yaml
Axllent\CMSTweaks\CMSTweaks:
  hide_help: false
```

## TinyMCE features

To limit the nasty side effects of copy/paste, TinyMCE has the following two options set:


```yaml
Axllent\CMSTweaks\CMSTweaks:
  invalid_elements: "div"
  extended_valid_elements: "span[!class|!style],p[class|style]"
```

These completely overrule any existing `HTMLEditorConfig` that was set. If you want to revert back to the system default, set both of these to `false`.

All other editor options are inherited from `HTMLEditorConfig`.
