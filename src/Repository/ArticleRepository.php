<?php

namespace App\Repository;

use Redis;

class ArticleRepository
{
    protected $redis;
    protected $prefix;

    public function __construct(Redis $redis, $prefix)
    {
        $this->redis  = $redis;
        $this->prefix = $prefix;
    }

    public function import()
    {
        $keys = $this->redis->keys($this->prefix . ':*');
        foreach ($keys as $key) {
            $this->redis->del($key);
        }

        $articles = [
            ['title' => 'Track Google Analytics events on outbound links', 'lead' => '<p>For a project we required tracking of events for various user interactions. The goal was to provide insights in user behaviour for certain sections of the site, but most of these interactions included "outbound links": hyperlinks pointing to a URL. Simply tracking the events with an <code>onClick</code> handler will not work in this case, so I wrote a simple wrapper to solve this problem.</p>', 'body' => '<p>Bla</p>'],
            ['title' => 'Managing ssh known hosts with ansible', 'lead' => '<p>Today I was looking how you can populate an ssh <code>known_hosts</code> file with ansible. For ssh access (pulling git repositories) I need to accept a fingerprint and I do not want to do this manually for every target. Therefore, I want to populate the known_hosts file so ssh can connect to them without interaction. And in fact, it was quite easy to achieve.</p>', 'body' => ''],
            ['title' => 'On leaving your own business', 'lead' => '<p>This week I will leave my own business. It has been prepared for 3 months, but now it is getting really close. I am ready for the next step and I am honestly happy the company will keep running without me. However, this period felt like a roller coaster and I would like to share some thoughts how such a process could be guided (or the alternative title for this post: "what I learned from my mistakes").</p>', 'body' => '<p>     I think the exit went pretty OK; there are still a couple of loose ends to tighten up, but those are foreseeable and can be dealt with in the time period that\'s left. Although I said it went "pretty OK", the last few months were extremely intense. and that was caused by either how the company was prepared for such event or how we went through the process. I really hope there is some advice here which helps entrepreneurs in preparing themselves for such a decision. At least, I would do it differently the second time I start a business.</p><h2>Independence</h2><p>     One of the major problems that arose quickly is my role in the company. I was a founder and not the only one. However, my role was tightly bound to the services we offered and this made the split up quite difficult.</p><p>     if you are freelancing, this is obviously intertwined with the business. But if you are (co)founder together with other people, you have to be aware of this issue. My case is a split up, but what if a founder is absent for a prolonged period of time due to illness? or what if, even worse, a founder is hit by a van?</p><p>     Make sure you can always keep it running, nonetheless the consequences of a founder leaving the office. A few methods:</p><ol><li>All decisions, plans, quotes, reports etcetera should be accessible for n+1 persons. Keep it on a share, in the cloud, have a backup: with whatever method, make sure that more than one can access important files.</li>    <li>Don\'t keep too many people around with very specific information or knowledge. You can\'t make sure everybody knows everything, but if all founders (or employees!) are working on their own island, you can never mitigate the loss of such person. The solution? Keep each other updated, talk, document your work and keep track of what you are doing!</li>    <li>Think about scale. It is hard to share knowledge if you are with a few people. With two or three persons working, there is a great amount of dependence onto each other; but with ten of twenty people, this is already greatly reduced. So: prepare for a little bit of scale right from the start. It obviously helps your business grow in capacity, but it also makes you more resistant against information loss.</li></ol><h2>Script the exit</h2><p>     It is no fun to leave. Not for the one leaving, nor for the one(s) keeping behind. You better talk about such event <em>before</em> it happens. When you start your company, it will eventually end for you (whether you quit, sell or retire). Think about it now: how would you like to deal with it at that moment?</p><p>     When the moment is there to quit, there is a lot going around. To minimize the energy lost on discussions you could have had prepared, simply talk about it before it happens. How are you and your partners thinking about leaving the business? if such event happens, what are the important items to deal with?</p><p>     You do not need to make decisions at that time, but you can agree about the script. Create a mutual understanding of all the issues you need to deal with and try to put them in a logical order. When the moment arrives you need to talk about an exit, there will be a script for you.</p><p>     A few topics you likely want to talk about:</p><ol><li>Shares</li>    <li>Capital and profit</li>    <li>Responsibilities, risks and perhaps future liability</li>    <li>Goodwill</li></ol><h2>Preparation time</h2><p>     The only thing we agreed upon when we started the company, was the time between the notice to leave and the actual goodbye. We decided back then we had to take at least three months. Looking back, I would recommend this is the maximum time to consider. The longer you take, the longer you are getting annoyed by the time it takes. My advice is to stick with two months, which should be enough. Three is the maximum to consider.</p><p>     When you are preparing to leave, you will have meetings with your (co)founders and other employees. Structure all conversions properly; for example by having a meeting weekly to talk about the progress. do not have meetings on an incidental base, as it doesn\'t help in the progress.</p><p>     During the meetings, structure the contents as well. Make sure all participants in the meetings list their own topics they want to discuss. This way, you do not have one person forcing the agenda. Ask around what should be on the agenda and try to keep everyone involved.</p><p>     After the meeting, give yourself some time to write down the important stuff discussed. It will give you an overview of the progress and if anything is unclear, you are able to look and read back what has been said already. Just don\'t forget to notice every member of the meeting about the minutes!</p><h2>Decision funnel</h2><p>     During the preparation time you will have a lot of conversations. There are many topics to deal with, so you talk about a lot of stuff. Don\'t keep it that way. When talking, you will quickly notice you agree upon a lot of things (and you probably agree on more topics than you disagree with!). My advice, write those down, confirm them together and do not discuss that same matter over and over again.</p><p>     It is mentally a tough job to keep discussing all the different topics. To alleviate your subconscious mind, make a decision funnel. It helped me extremely to keep the overview and to be (kind-of) fresh for every new topic to discuss. It is hard to deal with 100 things at the same time, but dealing with them one by one is much, much easier.</p><p>     A strategy I worked on was to summarize every meeting at the end. List all topics you discussed and their outcomes: on what did you agree and what is still left to be discussed? Then, write all agreements down and let everyone involved confirm those agreements. My advice: agree on the decision funnel as soon as possible; then agree to not discuss agreements again. It will be the first step forward for you.</p><h2>Upfront expectations</h2><p>     As a founder leaving, you care about the business. Probably you still care while you decided to leave. There is literally no barrier to keep being involved in the business <em>after </em>you left. But don\'t. Be aware that you have made the decision and <strong>what happens next is not your responsibility</strong>.</p><p>     It is very easy to fall back into common habits, both for you and your partners. They can ask you gazillion questions about the new direction to go to, the new partner to choose, a new business strategy to test out. Due to that common habit, you are likely to think along with them and answer their questions, but be very strict: after the split up, you are not involved any more.</p><p>     I have made this "mistake" and it cost a lot amount of time and energy, all I have spent onto something which isn\'t mine to decide. The very hard lesson to learn here: do not leave upfront expectations. If you are going away, make it directly clear <em>when</em> and <em>how</em> so your partners are not left behind with an illusion you are still available.</p><h2>TL;DR</h2><p>     Of course the complete post can be summarized as "define better rules upfront when starting your business". However, I am certain there are many, many companies which didn\'t thought through this completely. I cannot blame them: if it is your first time, you don\'t have the experience. And if you have the experience, it is still not a fun exercise to talk about quitting and problems when you are full of energy starting a new venture.</p><p>     So the short summary: make sure you have thought about what happens when a (co-)founder leaves. Make sure you structure the process, create a funnel for all the decisions to be made. And keep an open relationship: have a mutual understanding for each other — do not react based on emotions and discuss any problems you have openly.</p><h2>Why did I left?</h2><p>     The final question I did not answer is why <em>I</em> left. Currently, my ambitions cannot be fulfilled within my company. I like what I did, but I cannot imagine doing this for many more years. And doing what I love, cannot be done (currently) in my company. There are no hard feelings, just the simple maths that it is the best I quit my company now.</p><p>     Discuss this post on <a href="http://news.ycombinator.com">Hacker News</a>.</p>'],
            ['title' => 'Enable composer bash completion on Ubuntu', 'lead' => "<p>The <a href=\"https://github.com/iArren/composer-bash-completion\">composer bash completion</a> from <a href=\"https://github.com/iArren\">iArren</a> is fantastic, but is doesn't work out of the box on Ubuntu (14.04). I found out this is due to an old version of <code>bash-completion</code> on Ubuntu and with a simple fix I got it working.</p>", 'body' => '<p>      The cool thing about iArren\'s bash completion is not only the ablity to autocomplete the composer methods (<code>install</code>, <code>update</code> and so on) but it can query packages too. It uses <code>composer show -a</code> to get a list of all known packages. Thereafter, it lists all available versions of that package.</p><p>      <img src="https://juriansluiman.nl/assets/images/blog/61deef609bb58be9a13363d5f21cdeb5c257b457_54a29e7226231" class="full-width"></p><p>      The <a href="http://bash-completion.alioth.debian.org/">bash-completion project</a> ships with <a href="https://github.com/brechtm/bash-completion/blob/master/bash_completion">some functions</a> the completion files can use. A recent version comes with a function called <code>_init_completion()</code>, but is not included in <code>/etc/bash_completion</code> for some kind of reason. </p><p>      The problem here is the composer autocomplete uses the <code>_init_completion()</code> and it\'s not available on my machine! Other Ubuntu users have a similar issue and apparently this is due to an old version of bash or bash-completion shipped with 14.04. I am not sure if this is still the case for 14.10.</p><p>      The simple fix is to load the function yourself and source it from your <code>.bashrc</code>. Create a file <code>~/.bash_completion</code> with this content:</p><pre><code style="font-size:0.6em;">## Added because old bash-completion versions doesn\'t have the ## _init_completion() function# Initialize completion and deal with various general things: do file# and variable completion where appropriate, and adjust prev, words,# and cword as if no redirections exist so that completions do not# need to deal with them.  Before calling this function, make sure# cur, prev, words, and cword are local, ditto split if you use -s.## Options:#     -n EXCLUDE  Passed to _get_comp_words_by_ref -n with redirection chars#     -e XSPEC    Passed to _filedir as first arg for stderr redirections#     -o XSPEC    Passed to _filedir as first arg for other output redirections#     -i XSPEC    Passed to _filedir as first arg for stdin redirections#     -s          Split long options with _split_longopt, implies -n =# @return  True (0) if completion needs further processing, #          False (&gt; 0) no further processing is necessary.#_init_completion() {    local exclude flag outx errx inx OPTIND=1    while getopts "n:e:o:i:s" flag "$@"; do        case $flag in            n) exclude+=$OPTARG ;;            e) errx=$OPTARG ;;            o) outx=$OPTARG ;;            i) inx=$OPTARG ;;            s) split=false ; exclude+== ;;        esac    done    # For some reason completion functions are not invoked at all by    # bash (at least as of 4.1.7) after the command line contains an    # ampersand so we don\'t get a chance to deal with redirections    # containing them, but if we did, hopefully the below would also    # do the right thing with them...    COMPREPLY=()    local redir="@(?([0-9])&lt;|?([0-9&amp;])&gt;?(&gt;)|&gt;&amp;)"    _get_comp_words_by_ref -n "$exclude&lt;&gt;&amp;" cur prev words cword    # Complete variable names.    if [[ $cur =~ ^(\$\{?)([A-Za-z0-9_]*)$ ]]; then        [[ $cur == *{* ]] &amp;&amp; local suffix=} || local suffix=        COMPREPLY=( $( compgen -P ${BASH_REMATCH[1]} -S "$suffix" -v -- \            "${BASH_REMATCH[2]}" ) )        return 1    fi    # Complete on files if current is a redirect possibly followed by a    # filename, e.g. "&gt;foo", or previous is a "bare" redirect, e.g. "&gt;".    if [[ $cur == $redir* || $prev == $redir ]]; then        local xspec        case $cur in            2\'&gt;\'*) xspec=$errx ;;            *\'&gt;\'*) xspec=$outx ;;            *\'&lt;\'*) xspec=$inx ;;            *)                case $prev in                    2\'&gt;\'*) xspec=$errx ;;                    *\'&gt;\'*) xspec=$outx ;;                    *\'&lt;\'*) xspec=$inx ;;                esac                ;;        esac        cur="${cur##$redir}"        _filedir $xspec        return 1    fi    # Remove all redirections so completions don\'t have to deal with them.    local i skip    for (( i=1; i &lt; ${#words[@]}; )); do        if [[ ${words[i]} == $redir* ]]; then            # If "bare" redirect, remove also the next word (skip=2).            [[ ${words[i]} == $redir ]] &amp;&amp; skip=2 || skip=1            words=( "${words[@]:0:i}" "${words[@]:i+skip}" )            [[ $i -le $cword ]] &amp;&amp; cword=$(( cword - skip ))        else            i=$(( ++i ))        fi    done    [[ $cword -eq 0 ]] &amp;&amp; return 1    prev=${words[cword-1]}    [[ $split ]] &amp;&amp; _split_longopt &amp;&amp; split=true    return 0}</code></pre><p>      This is extracted from the source file <a href="https://github.com/brechtm/bash-completion/blob/6e08dc550474c66bc09a539d7f35d2f58891be9c/bash_completion#L608-L695">hosted on Github</a>. Now, load this file from the <code>~/.bashrc</code> and include <code>~/.bash_completion</code> only when the <code>_init_completion()</code> is unknown:</p><pre><code># make sure we have the _init_completion() functionif type -t _init_completion | grep -q \'^function$\'; then    . ~/.bash_completionfi</code></pre><p>    The last step is to download the <a href="https://github.com/iArren/composer-bash-completion/blob/master/composer">composer autocompletion file</a> and save it as <code>/etc/bash_completion.d/composer</code>. Now reload bash (with <code>source ~/.bashrc</code>) and you\'re good to go!</p>'],
        ];

        $id = 1;
        foreach ($articles as $article) {
            $this->redis->lpush($this->key('articles'), $id);
            $this->update($id, $article);
            $id++;
        }
    }

    public function getTotalCount()
    {
        return $this->redis->llen($this->key('articles'));
    }

    public function fetchRecent($limit)
    {
        return $this->fetchOffset(0, $limit -1);
    }

    public function fetchArticle($id)
    {
        $key = $this->key('article:' . $id);
        $article = $this->redis->get($this->key('article:' . $id));

        if (!$article) {
            return false;
        }

        return json_decode($article, true);
    }

    public function fetchAll()
    {
        return $this->fetchOffset(0, -1);
    }

    public function fetchOffset($start, $end)
    {
        $articles = $this->redis->lRange($this->key('articles'), $start, $end);

        $result = [];
        foreach ($articles as $id) {
            $result[] = json_decode($this->redis->get($this->key('article:' . $id)), true);
        }

        return $result;
    }

    public function persist(array $data)
    {
        $id = $this->redis->lindex($this->key('articles'), -1);
        $id = $id ?: 1;

        $this->update($id, $data);
        return $id;
    }

    public function update($id, array $data)
    {
        $key     = $this->key('article:' . $id);
        $article = $this->redis->set($this->key('article:' . $id), json_encode($data + ['id' => $id]));
    }

    private function key($name)
    {
        return sprintf('%s:%s', $this->prefix, $name);
    }
}
