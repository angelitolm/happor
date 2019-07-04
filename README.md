# Happor
Is the little Composer repository generator based on Satis.

![Happor](https://scontent-mia3-1.xx.fbcdn.net/v/t1.0-9/50467899_565663080569568_7706252294982991872_n.png?_nc_cat=109&_nc_oc=AQm7iGYrJVPzwdV1v-iQlfrVy8uWAU2a45yVi8YkL-QxTr4EO4TSbT8zAm8DrFXBD0A&_nc_ht=scontent-mia3-1.xx&oh=9e61c15f50075e8e2022ee79fdc80bdd&oe=5D7AB5C6)

<p><a href="https://travis-ci.org/angelitolm/happor" rel="nofollow"><img src="https://camo.githubusercontent.com/770aad8227e550bb1958b38bce5430dea32f4bb9/68747470733a2f2f7472617669732d63692e6f72672f636f6d706f7365722f73617469732e7376673f6272616e63683d6d6173746572" alt="Build Status" data-canonical-src="https://travis-ci.org/angelitolm/happor.svg?branch=master" style="max-width:100%;"></a>
<a href="https://codecov.io/gh/angelitolm/happor" rel="nofollow"><img src="https://camo.githubusercontent.com/98acb1573f5e05041077f2e102ced22a64194b63/68747470733a2f2f636f6465636f762e696f2f67682f636f6d706f7365722f73617469732f6272616e63682f6d61737465722f67726170682f62616467652e737667" alt="codecov" data-canonical-src="https://codecov.io/gh/angelitolm/happor/branch/master/graph/badge.svg" style="max-width:100%;"></a></p>
<h2><a id="user-content-run-from-source" class="anchor" aria-hidden="true" href="#run-from-source"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>Run from source</h2>
<ul>
<li>Install happor: <code>composer create-project angelitolm/happor:dev-master</code></li>
<li>Build a repository: <code>php bin/happor build &lt;configuration-file&gt; &lt;output-dir&gt;</code></li>
</ul>
<h2>Installation</h2>
<h3>Step 1</h3>
<p>The first step is to clone it from github</p>

<pre>composer create-project angelitolm/happor --stability=dev --keep-vcs</pre>

<h3>Step 2</h3>
<p>Once it is finished we will initialize it so that it creates the configuration file (it could be done manually, too, but take advantage of the tool).</p>

<pre>bin/happor init</pre>

<p>We will assign the name and url of our local repository. The URL must have been previously created in a vhost beforehand.</p>

<p><strong>Example:</strong>
<pre><VirtualHost *:80>
    ServerAdmin admin@happor.example.com
    ServerName happor.example.com
    ErrorLog /var/log/error-happor.log
    LogLevel warn
    CustomLog /var/log/access-happor.log combined
    DocumentRoot /var/www/html/happor/web
    <Directory /var/www/html/happor/web>
       DirectoryIndex index.html index.php
       Options Indexes FollowSymLinks MultiViews
       AllowOverride all
       Order deny,allow
       Deny from all
       Allow from all
    </Directory>
</VirtualHost></pre>

<p>When initializing it, a file named happor.json has been created with this content:</p>

<pre>{
    "name": "Happor, The little PHP Package Repository",
    "homepage": "http://happor.example.com",
    "repositories": [],
    "require-all": true
}</pre>


<h3>Step 3</h3>
<p>Now it's time to add modules that are from remote repositories, which are those that will be served from Happor instead of using the original remote repository.</p>

<p>For that, while it can also be done manually, we will continue with the console of Happor. For the example I have created two modules (only the basic structure) that have their private repositories in Github).</p>

<pre>bin/happor add git@github.com:symfony/symfony-standard.git</pre>

<p><strong>Manual example:</strong></p>
<p>We must edit the file happor.json and add the following:</p>

<pre>{
    "type": "vcs",
    "url": "git@github.com:symfony/symfony-standard.git"
}</pre>

<p>Staying as follows:</p>

<pre>{
    "name": "Happor, The little PHP Package Repository",
    "homepage": "http://happor.example.com",
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:symfony/symfony-standard.git"
        }
    ],
    "require-all": true
}</pre>

<h3>Step 4</h3>
<p>Assuming we have added all the repositories that we needed, now we have to do our first build. For this, we execute:</p>

<pre>bin/happor build happor.json web -n</pre>

<p>To understand the command:</p>
<ul>
    <li><strong>bin/happor:</strong> is the console.</li>
    <li><strong>build:</strong> is the command that we are wanting to execute.</li>
    <li><strong>happor.json:</strong> the name of the file from which the parameters will be taken.</li>
    <li><strong>web:</strong> the directory in which the build and the web interface will be created.</li>
    <li><strong>-n:</strong> we use the parameter to take credentials of the user who is executing the command (the ssh keys, for example, to access the repositories added).</li>
</ul>

<p>Now we can navigate with our browser our instance of Happor and we would have the packages that we had added.</p>
http://happor.example.com
<h2><a id="user-content-run-as-docker-container" class="anchor" aria-hidden="true" href="#run-as-docker-container"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>Run as Docker container</h2>
<p>Pull the image:</p>
<div class="highlight highlight-source-shell"><pre>docker pull composer/happor</pre></div>
<p>Run the image:</p>
<div class="highlight highlight-source-shell"><pre>docker run --rm -it -v /build:/build composer/happor</pre></div>
<blockquote>
<p>Note: by default it will look for a configuration file named <code>happor.json</code>
inside the <code>/build</code> directory and dump the generated output files in
<code>/build/output</code>.</p>
</blockquote>
<p>Run the image (with Composer cache from host):</p>
<div class="highlight highlight-source-shell"><pre>docker run --rm -it -v /build:/build -v <span class="pl-smi">$COMPOSER_HOME</span>:/composer composer/happor</pre></div>
<p>If you want to run the image without implicitly running Happor, you have to
override the entrypoint specified in the <code>Dockerfile</code>:</p>
<div class="highlight highlight-source-shell"><pre>docker run --rm -it --entrypoint /bin/sh composer/happor</pre></div>
<h2><a id="user-content-purge" class="anchor" aria-hidden="true" href="#purge"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>Purge</h2>
<p>If you choose to archive packages as part of your build, over time you can be
left with useless files. With the <code>purge</code> command, you can delete these files.</p>
<div class="highlight highlight-source-shell"><pre>php bin/happor purge <span class="pl-k">&lt;</span>configuration-file<span class="pl-k">&gt;</span> <span class="pl-k">&lt;</span>output-dir<span class="pl-k">&gt;</span></pre></div>
<blockquote>
<p>Note: don't do this unless you are certain your projects no longer reference
any of these archives in their <code>composer.lock</code> files.</p>
</blockquote>
<h2><a id="user-content-updating" class="anchor" aria-hidden="true" href="#updating"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>Updating</h2>
<p>Updating Happor is as simple as running <code>git pull &amp;&amp; composer update</code> in the
Happor directory.</p>
<p>If you are running Happor as a Docker container, simply pull the latest image.</p>
<h2><a id="user-content-contributing" class="anchor" aria-hidden="true" href="#contributing"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>Contributing</h2>
<p>Please note that this project is released with a <a href="http://contributor-covenant.org/version/1/4/" rel="nofollow">Contributor Code of Conduct</a>.
By participating in this project you agree to abide by its terms.</p>
<p>Fork the project, create a feature branch, and send us a pull request.</p>
<h2><a id="user-content-authors" class="anchor" aria-hidden="true" href="#authors"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>Authors</h2>
<p>See the list of <a href="https://github.com/angelitolm/happor/contributors">contributors</a> who participate(d) in this project.</p>

<h2><a id="user-content-license" class="anchor" aria-hidden="true" href="#license"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg></a>License</h2>
<p>Happor is licensed under the MIT License - see the <a href="https://github.com/angelitolm/happor/blob/master/LICENSE">LICENSE</a> file for details</p>
