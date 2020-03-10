Setting up a local env for wp might take time so here is a quick info on place where u can make changes to the site directly.

## 1. CSS
 
GO to (https://www.earthhour.sg/wp-admin/customize.php)[https://www.earthhour.sg/wp-admin/customize.php], to add any css styles to any part of the site. (including openletter, homepage, maps etc). There is no need to PR.


## 2. JS **This is only for your reference**

Different features are built with different JS libraries

**openletter** is built with *vuejs*. You can find the template code (html and some inlined logic) here - https://github.com/wwf-sg/eh2020/blob/master/wp-content/themes/eh2020/partials/voice.php and app logic here https://github.com/wwf-sg/eh2020/blob/master/wp-content/themes/eh2020/src/app.js. 


**Lightsout maps** feature uses custom jQuery code.
All the code related to lightsout map feature can be found here - https://github.com/wwf-sg/eh2020/tree/master/wp-content/plugins/w2gm and JS is under `/resources/js`. 

The custom submisson form for lightsout map tailored for our requirement is here - https://github.com/wwf-sg/eh2020/blob/master/wp-content/themes/eh2020/w2gm-plugin/templates/w2gm_fsubmit/submitlisting_step_create.tpl.php

**Image genereation** 
Code for the image generation can be found here - https://github.com/wwf-sg/eh2020/tree/master/wp-content/themes/eh2020/functions/generate-image


## 3. PHP

### Homepage

### Lightout

### Aboout




