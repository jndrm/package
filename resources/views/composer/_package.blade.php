<div class="card {{$package->abandoned ? 'card-danger' : 'card-info'}}">
    <h3 class="card-header package-title" id="{{ $package->highest->name }}">
        <a href="#{{ $package->highest->name }}" class="anchor">
            <svg class="octicon-link" width="16" height="16">
                <use xlink:href="#octicon-link"></use>
            </svg>
            {{ $package->highest->name }}
        </a>

        @if ($package->abandoned)
        <p class="abandoned">
            <strong>Abandoned!</strong>
            Package is abandoned, you should avoid using it.
            @if ($package->replacement)
            Use {{ $package->replacement }} instead.
            @else
            No replacement was suggested.
            @endif
        </p>
        @endif

    </h3>

    <div class="card-body">
        @if ($package->highest->description)
        <p>{{ $package->highest->description }}</p>
        @endif

        @if ($package->highest->keywords)
        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>Keywords</strong></div>
            <div class="col-12 col-sm-10">{{ implode(',', $package->highest->keywords) }}</div>
        </div>
        @endif

        @if ($package->highest->homepage)
        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>Homepage</strong></div>
            <div class="col-12 col-sm-10"><a href="{{ $package->highest->homepage }}">{{ $package->highest->homepage }}</a></div>
        </div>
        @endif

        @if ($package->highest->license)
        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>License</strong></div>
            <div class="col-12 col-sm-10">{{ implode(',', $package->highest->license) }}</div>
        </div>
        @endif

        @if ($package->highest->authors)
        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>Authors</strong></div>
            <div class="col-12 col-sm-10">
                @foreach ($package->highest->authors as $author)
                <?php
                    $author = (object) $author;
                ?>
                @if (isset($author->homepage) && $author->homepage)
                <a href="{{ $author->homepage }}">{{ $author->name }}</a>
                @else
                {{ $author->name }}
                @endif
                {{$loop->last ? '' : ','}}
                @endforeach
            </div>
        </div>
        @endif

        @if ($package->highest->support)
        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>Support</strong></div>
            <div class="col-12 col-sm-10">
                <ul>
                    @foreach ($package->highest->suport as $type => $supportUrl)
                    <li>{{ strtoupper($type) }}: <a href="{{ $supportUrl }}">{{ $supportUrl }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>Releases</strong></div>
            <div class="col-12 col-sm-10">
                @foreach ($package->versions as $version)
                <?php
                    $branchAlias = array_get($version->extra, 'branch-alias', $version->prettyVersion);
                    if ($branchAlias) {
                        $branchAlias = ", branch-alias: " . $branchAlias;
                    }
                ?>
                @if ($package->highest->type == 'metapackage')
                {{ $version->prettyVersion }}
                @elseif($version->distType)
                <a href="{{ $version->distUrl }}" title="dist-reference: {{ $version->distReference }}{{ $branchAlias }}">{{ $version->prettyVersion }}</a>
                @else
                <a href="{{ $version->sourceUrl }}" title="source-reference: {{ $version->sourceReference }}{{ $branchAlias }}">{{ $version->prettyVersion }}</a>
                @endif
                {{$loop->last ? '' : ','}}
                @endforeach
            </div>
        </div>

        <?php
            $packageDependencies = array_get($dependencies, $name);
        ?>
        @if (count($packageDependencies))
        <div class="row">
            <div class="col-2 text-xs-left text-sm-right"><strong>Required by</strong></div>
            <div class="col-12 col-sm-10">
                <ul>
                    @foreach ($packageDependencies as $dependency)
                    <li><a href="#{{ $dependency }}">{{ $dependency }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
