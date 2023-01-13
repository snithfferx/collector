<footer class="main-footer text-sm">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        <b>Version</b> {{$footer.data.version}} <b>Theme</b>
        {{foreach $footer.data.theme as $theme}}
            <a href="{{$theme.url}}" rel="external" target="_blank">{{$theme.name}}</a>
            {{if !$theme@last}} | {{/if}}
        {{/foreach}}
        <b>Technologies</b>
        {{foreach $footer.data.technology as $coop}}
            <a href="{{$coop.url}}" rel="external" target="_blank">{{$coop.name}}</a>
            {{if !$coop@last}} | {{/if}}
        {{/foreach}}
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; {{$footer.data.year}}-2023 <a href="{{$footer.data.companyURL}}" rel="external"
            target="_blank">{{$footer.data.company}}</a>.</strong> All rights reserved.
</footer>