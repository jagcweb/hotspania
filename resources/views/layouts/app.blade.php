<!doctype html>
<html lang="es" style="background:#111!important;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hotspania">
    <meta name="author" content="jagcweb">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Hotspania') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/templatemo-hexashop.css') }}">

    <link rel="stylesheet" href="{{ asset('css/home-styles.css') }}">

    <link rel="stylesheet" href="{{ asset('css/owl-carousel.css') }}">

    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>
        .modal {
            z-index: 99999999999999;
        }
        ::selection {
            background-color: #F65807; /* Change this to your desired background color */
        }

        .search-container {
          position: relative;
          display: flex;
          align-items: center;
        }
    
        .search-input {
          margin-top:10px;
          padding: 5px 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
          margin-right: 10px;
          width: 0;
        }
    
        .search-toggle {
          padding: 0.5rem 1rem;
          display: flex;
          align-items: center;
        }
    
        .search-icon {
          cursor: pointer;
          font-size: 16px;
        }
        .city-dropdown .form-control {
            font-size: 13px;
        }
        .city-dropdown .dropdown-item {
            font-size: 13px;
        }
        .city-list .dropdown-item {
            display: none;
            font-size: 13px;
        }
        .city-list.searching .dropdown-item {
            display: none; /* Todos ocultos por defecto cuando se busca */
        }
        .city-list.searching .dropdown-item.show {
            display: block; /* Solo mostrar los que coinciden */
        }
        .city-dropdown {
            min-width: 160px !important;
        }
    </style>
</head>
<body>
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    dropdownList.forEach(dropdown => {
                        dropdown.hide();
                    });
                }
            });

            // City search functionality
            const citySearch = document.querySelector('.city-search');
            const cityList = document.querySelector('.city-list');
            const dropdownItems = document.querySelectorAll('.city-list .dropdown-item');

            if (citySearch) {
                citySearch.addEventListener('input', function() {
                    const searchText = this.value.toLowerCase();
                    
                    // Si hay texto, añadir clase searching
                    cityList.classList.toggle('searching', searchText.length > 0);
                    
                    dropdownItems.forEach(item => {
                        const cityName = item.textContent.toLowerCase();
                        const matches = cityName.includes(searchText);
                        
                        if (searchText.length > 0) {
                            item.style.display = matches ? 'block' : 'none';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                // Prevent dropdown from closing when clicking the search input
                citySearch.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Handle city selection
            dropdownItems.forEach(item => {
                item.addEventListener('click', function() {
                    const cityName = this.textContent.trim().toLowerCase();
                    
                    // Set cookie with necessary attributes
                    document.cookie = "selected_city=" + cityName + 
                        "; path=/; SameSite=Lax; secure=true; max-age=2592000";
                    
                    // Mantener los parámetros actuales de la URL
                    const currentUrl = new URL(window.location.href);
                    window.location.href = currentUrl.href;
                });
            });
        });
    </script>

    <nav style="z-index: 10;">
        <a href="{{ route('home') }}">
            <img class="img_logo" src="{{ asset('images/logo.png') }}" alt="Logo"/>
        </a>
        @if(str_contains(url()->current(), '/home'))
            <li class="nav-item search-container">
                <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center">
                    <a href="#" class="nav-link search-toggle">
                        <i class="fas fa-magnifying-glass search-icon"></i>
                    </a>
                    <input type="text" name="search" class="search-input {{ !request('search') ? 'd-none' : '' }}" placeholder="Buscar nombre..." value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="nav-link">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </li>

            @php $selected_city = isset($_COOKIE['selected_city']) ? $_COOKIE['selected_city'] : ''; @endphp
            <li class="nav-item" style="font-size:14px; margin-top:10px;">
                <div class="dropdown">
                    <button class="btn dropdown-toggle text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ ucfirst($selected_city) ?: 'Ciudad' }}
                    </button>
                    <div class="dropdown-menu p-2 city-dropdown">
                        <input type="text" class="form-control mb-2 city-search" placeholder="Buscar ciudad..." autocomplete="off">
                        <div class="city-list">
                            @php $cities = \App\Models\City::orderBy('id', 'asc')->get(); @endphp
                            @foreach($cities as $c)
                                <button type="button" class="dropdown-item">{{$c->name}}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </li>


        @endif
         <ul class="list ullist">
            {{--<li><a class="text-white" href="#">Inicio</a></li>
            <li><a class="text-white" href="#">Mi cuenta</a></li>
            <li>
            <form id="logout-form" action="{{ url('logout') }}" method="POST">
              {{ csrf_field() }}
              <a href="javascript:{}" onclick="document.getElementById('logout-form').submit();" class="text-white">
                  <span>Cerrar sesión</span>
              </a>
            </li>--}}
            <li>
              @if(\Auth::user())
                @if(\Auth::user()->getRoleNames()[0] == "admin")
                  <a href="{{ route('admin.citychanges') }}" style="display: flex; flex-direction:row; justify-content:center; align-items:center;">
                @else
                  <a href="{{ route('account.index') }}" style="display: flex; flex-direction:row; justify-content:center; align-items:center;">
                @endif
                @php $frontimage = \App\Models\Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->first(); @endphp
                @if(is_object($frontimage))
                    @if(!is_null($frontimage->route_gif))
                      <img class="rounded-circle"  style="width:40px; height:40px; border-radius:9999px;" src="{{ route('home.gifget', ['filename' => $frontimage->route_gif]) }}" />
                    @else
                      <img class="rounded-circle"  style="width:40px; height:40px; border-radius:9999px;" src="{{ route('home.imageget', ['filename' => $frontimage->route]) }}" />
                    @endif
                @else
                    <img src="{{ asset('images/user.jpg') }}" class="rounded-circle" style="width:40px; height:40px; border-radius:9999px;"/>
                @endif
                    <i class="fa-solid fa-bars" style="font-size:18px; color:#fff; margin-left:15px; margin-top:5px;"></i>
                </a>
               @else
                @if(!str_contains(url()->current(), '/login') && !str_contains(url()->current(), '/register'))
                    <a title="Anúnciate" href="{{ route('user.register', ['step' => 1]) }}" class="btn btn-primary mt-2" style="background:#f36e00!important; color:#fff;min-width: 100px;min-height: 35px;display: flex;align-items: center;justify-content: center;font-size: 13px;">
                        Anúnciate
                        <i class="fa-solid fa-rocket ml-1"></i>
                    </a>
                @endif
              @endif
            </li>
          </form>
        </ul>
        <li class="lilist">
            <a class="ml-3" href="javascript:void(0);">
                <i class="fa-solid fa-bars text-white" style="font-size: 22px; line-height:20px;"></i>
            </a>
        </li>
    </nav>

    @include('partial_msg')
    <div id="app" style="margin-top: 85px; min-height: 82vh;  display: flex;
    flex-direction: column;">
        <main>
            @yield('content')
        </main>
    </div>

    <div style="margin-top:60px;"></div>

<div class="footer mt-3 d-none">
  <div class="bubbles">
    <div class="bubble" style="--size:3.9544746530484645rem; --distance:9.305364258132585rem; --position:78.42261521222024%; --time:2.323553913631863s; --delay:-3.9154029074929033s;"></div>
    <div class="bubble" style="--size:4.071839379938712rem; --distance:8.029740377986665rem; --position:100.54530778248105%; --time:3.085955849628327s; --delay:-3.3786024579902865s;"></div>
    <div class="bubble" style="--size:2.7279834451353135rem; --distance:9.401484242458832rem; --position:89.92587364918325%; --time:3.008228122784652s; --delay:-3.2646606399515674s;"></div>
    <div class="bubble" style="--size:3.5626726659450654rem; --distance:7.200584660159665rem; --position:55.719607837639536%; --time:3.438939461251745s; --delay:-3.0249171561781285s;"></div>
    <div class="bubble" style="--size:2.937222427369585rem; --distance:6.430548660683604rem; --position:77.79971686829722%; --time:3.3214068933453307s; --delay:-2.6954521904653514s;"></div>
    <div class="bubble" style="--size:3.609517771217301rem; --distance:6.85290340412987rem; --position:85.90179896714264%; --time:3.1447126412342286s; --delay:-2.8978840554610956s;"></div>
    <div class="bubble" style="--size:5.799986909862801rem; --distance:8.773611337749957rem; --position:5.654481985015758%; --time:3.6841054077221678s; --delay:-2.3726814018215934s;"></div>
    <div class="bubble" style="--size:3.7156991791410596rem; --distance:7.341049257125789rem; --position:98.89129346754586%; --time:3.6999300271468s; --delay:-3.523371189481308s;"></div>
    <div class="bubble" style="--size:4.4200081902419335rem; --distance:8.895992513641225rem; --position:6.505157788898071%; --time:3.7735662874185687s; --delay:-2.6510654430404585s;"></div>
    <div class="bubble" style="--size:2.4481383975428685rem; --distance:6.449663840772116rem; --position:37.84907256366768%; --time:3.6517147514533486s; --delay:-3.136051294755563s;"></div>
    <div class="bubble" style="--size:2.134838979659909rem; --distance:6.1751710763796rem; --position:15.150976634970242%; --time:3.0287115207161706s; --delay:-3.0641549610281498s;"></div>
    <div class="bubble" style="--size:3.2143559919750957rem; --distance:7.4188519895527065rem; --position:23.70511007441871%; --time:2.244099944454262s; --delay:-3.142896170832736s;"></div>
    <div class="bubble" style="--size:5.303588068968311rem; --distance:6.933432002741981rem; --position:55.53405493317365%; --time:2.7189247911907293s; --delay:-2.7411199123329055s;"></div>
    <div class="bubble" style="--size:4.599402094852643rem; --distance:8.153144619976555rem; --position:61.04408157006236%; --time:2.7813761437781777s; --delay:-2.6424902675118878s;"></div>
    <div class="bubble" style="--size:4.3988571672804415rem; --distance:9.089284215852729rem; --position:11.810827331704296%; --time:3.623612829072027s; --delay:-3.2142370807363903s;"></div>
    <div class="bubble" style="--size:2.028117058119824rem; --distance:9.866145459567216rem; --position:16.829933984827647%; --time:3.8245805229219236s; --delay:-2.6408834575757782s;"></div>
    <div class="bubble" style="--size:5.164966860206377rem; --distance:7.543959250739103rem; --position:-0.3931664301618518%; --time:3.782688116779412s; --delay:-2.2566025614650873s;"></div>
    <div class="bubble" style="--size:2.3059755689737247rem; --distance:6.420766573350404rem; --position:22.248531314503758%; --time:3.350520619445293s; --delay:-2.8083306410054867s;"></div>
    <div class="bubble" style="--size:5.041598192524528rem; --distance:6.19363340477922rem; --position:51.68363386926925%; --time:3.546063401857492s; --delay:-2.5429361362081386s;"></div>
    <div class="bubble" style="--size:2.6305947856896794rem; --distance:6.947413053272024rem; --position:15.873863913608531%; --time:2.33339080705165s; --delay:-2.707126984559162s;"></div>
    <div class="bubble" style="--size:4.213604204976132rem; --distance:8.729076966805302rem; --position:62.84624639394127%; --time:3.422905873974523s; --delay:-2.573641869838826s;"></div>
    <div class="bubble" style="--size:5.083693344201993rem; --distance:6.477743703632292rem; --position:49.612558757319796%; --time:2.4508589633325997s; --delay:-3.127300181059405s;"></div>
    <div class="bubble" style="--size:5.5932029568417rem; --distance:9.674903874856522rem; --position:81.65219858208324%; --time:3.7736228809214927s; --delay:-2.24221722070744s;"></div>
    <div class="bubble" style="--size:5.717732983255812rem; --distance:6.117261073184593rem; --position:91.44362197075759%; --time:2.58657986345737s; --delay:-2.601283021581989s;"></div>
    <div class="bubble" style="--size:3.069297373107896rem; --distance:8.76508725860659rem; --position:82.66684716767473%; --time:2.881392560689389s; --delay:-2.595017638705996s;"></div>
    <div class="bubble" style="--size:5.0083799688514254rem; --distance:6.389926738794667rem; --position:13.155066451342716%; --time:3.466904771249107s; --delay:-2.0244277358220053s;"></div>
    <div class="bubble" style="--size:5.066172139016915rem; --distance:7.615848228660187rem; --position:82.79720523270251%; --time:2.090162305155469s; --delay:-3.9885613189407403s;"></div>
    <div class="bubble" style="--size:5.394423324689708rem; --distance:6.6094937747507565rem; --position:-3.7767511220620618%; --time:3.5390356250432786s; --delay:-3.4715826175314177s;"></div>
    <div class="bubble" style="--size:3.02519956493615rem; --distance:6.661711065163427rem; --position:5.036867072680732%; --time:3.2235644318115506s; --delay:-3.6196856809749436s;"></div>
    <div class="bubble" style="--size:3.529486362056872rem; --distance:8.783988065760804rem; --position:99.58998405299492%; --time:2.2234474106733275s; --delay:-2.6826998108055875s;"></div>
    <div class="bubble" style="--size:4.473261424685138rem; --distance:9.01849945642307rem; --position:49.74126591641169%; --time:3.144132471956965s; --delay:-3.9744674022186626s;"></div>
    <div class="bubble" style="--size:2.054956808428922rem; --distance:7.871870364680238rem; --position:74.40411825511764%; --time:2.1467982302496305s; --delay:-2.5961808469982928s;"></div>
    <div class="bubble" style="--size:5.189833741660833rem; --distance:7.554913039057741rem; --position:82.68924996656195%; --time:2.777532941989463s; --delay:-2.3226313322496583s;"></div>
    <div class="bubble" style="--size:3.4798433612000523rem; --distance:6.228934082540871rem; --position:4.633621744436276%; --time:3.5380892372325135s; --delay:-2.8686641838953877s;"></div>
    <div class="bubble" style="--size:3.7901788134445193rem; --distance:7.694897850328568rem; --position:103.34831577113563%; --time:2.2148226957519617s; --delay:-2.8774758673101744s;"></div>
    <div class="bubble" style="--size:5.095230960017322rem; --distance:9.033186564219205rem; --position:70.67933132742334%; --time:2.5910372083149147s; --delay:-3.591516461203131s;"></div>
    <div class="bubble" style="--size:5.3599170774410885rem; --distance:7.721760793462755rem; --position:79.04868206330544%; --time:2.4784251671806663s; --delay:-2.164504536133476s;"></div>
    <div class="bubble" style="--size:3.01088329714515rem; --distance:6.568888410158722rem; --position:99.25326521937332%; --time:2.6628426030032513s; --delay:-3.5747775409265534s;"></div>
    <div class="bubble" style="--size:4.362731647672353rem; --distance:8.289069792154473rem; --position:43.91126460442245%; --time:2.917325455721545s; --delay:-2.7407474634459748s;"></div>
    <div class="bubble" style="--size:3.2309809979343824rem; --distance:8.992184940694035rem; --position:66.39302751182738%; --time:3.6105457220516577s; --delay:-3.1273450336082043s;"></div>
    <div class="bubble" style="--size:4.1271252557127545rem; --distance:7.010924749981676rem; --position:75.90776653672934%; --time:2.71349575321637s; --delay:-3.0408279490536794s;"></div>
    <div class="bubble" style="--size:2.522743826283892rem; --distance:8.288582792395873rem; --position:16.527506647815606%; --time:3.2907537968092964s; --delay:-2.472341971498917s;"></div>
    <div class="bubble" style="--size:3.6520022899949347rem; --distance:7.435088578915138rem; --position:81.4601679571572%; --time:2.7630413773871942s; --delay:-3.5910970779576363s;"></div>
    <div class="bubble" style="--size:5.595771650202163rem; --distance:9.614646358402858rem; --position:68.60616351335283%; --time:2.0696118600487843s; --delay:-3.892771451744966s;"></div>
    <div class="bubble" style="--size:4.387386289893668rem; --distance:8.067856234571282rem; --position:102.6191528551666%; --time:3.1095872791569406s; --delay:-2.058314428656195s;"></div>
    <div class="bubble" style="--size:3.483609582194928rem; --distance:8.577133879460757rem; --position:50.46274061438694%; --time:2.784939885011714s; --delay:-3.1243539626002597s;"></div>
    <div class="bubble" style="--size:4.652788407681083rem; --distance:8.138989207524169rem; --position:36.84547930675765%; --time:2.7168874428981864s; --delay:-2.9380312588379276s;"></div>
    <div class="bubble" style="--size:5.960892769848324rem; --distance:7.13932797304346rem; --position:67.24980353645428%; --time:2.8753383546661455s; --delay:-2.020591073872551s;"></div>
    <div class="bubble" style="--size:3.9121658876973915rem; --distance:7.0216026609259625rem; --position:23.629144069789593%; --time:3.8823524698728287s; --delay:-2.1674235401972295s;"></div>
    <div class="bubble" style="--size:4.604683814735319rem; --distance:8.436365918969003rem; --position:87.5182108785563%; --time:3.02058234176672s; --delay:-2.2938269103104645s;"></div>
    <div class="bubble" style="--size:4.897854408424124rem; --distance:6.331698510167666rem; --position:69.9165412079847%; --time:3.5488436898398357s; --delay:-3.443602744111123s;"></div>
    <div class="bubble" style="--size:2.87324730742379rem; --distance:6.914359844646518rem; --position:13.721247128229944%; --time:3.133497442575976s; --delay:-3.62200038228955s;"></div>
    <div class="bubble" style="--size:5.570396247925144rem; --distance:7.5440040757664875rem; --position:65.1199802112234%; --time:3.1002905420435862s; --delay:-2.1496657492301976s;"></div>
    <div class="bubble" style="--size:3.5184259780283482rem; --distance:8.668220984346148rem; --position:36.236515559192114%; --time:3.44553349058065s; --delay:-2.3338329393197554s;"></div>
    <div class="bubble" style="--size:3.3792889013684078rem; --distance:6.128476444194828rem; --position:16.525571556287918%; --time:3.994804029560023s; --delay:-2.2681760109452407s;"></div>
    <div class="bubble" style="--size:4.630147514986441rem; --distance:6.776574407459384rem; --position:55.598563989560404%; --time:3.8526008688255526s; --delay:-3.662009447026132s;"></div>
    <div class="bubble" style="--size:2.5759498432206542rem; --distance:6.735343113200319rem; --position:69.33584576923167%; --time:2.1615196999987702s; --delay:-3.9004094225841452s;"></div>
    <div class="bubble" style="--size:2.3696315730643396rem; --distance:9.431102360248632rem; --position:14.207240364243564%; --time:2.501571949784455s; --delay:-3.063891267553755s;"></div>
    <div class="bubble" style="--size:5.9515725098449455rem; --distance:6.215565475758735rem; --position:85.9069808530963%; --time:2.20620909928042s; --delay:-2.607172470571246s;"></div>
    <div class="bubble" style="--size:3.236676539436937rem; --distance:9.326200506488023rem; --position:58.33969057730707%; --time:3.327487989451603s; --delay:-2.373570876846067s;"></div>
    <div class="bubble" style="--size:4.853754598794872rem; --distance:8.628567343638043rem; --position:53.41626611713379%; --time:3.3388451257316376s; --delay:-3.9181892178742297s;"></div>
    <div class="bubble" style="--size:5.07117418252766rem; --distance:8.556131091630526rem; --position:54.12478409711636%; --time:2.675619687753849s; --delay:-3.306795697371385s;"></div>
    <div class="bubble" style="--size:3.5382281193372416rem; --distance:6.6032494822092005rem; --position:99.42546605463149%; --time:3.814768215608444s; --delay:-3.0141390960405348s;"></div>
    <div class="bubble" style="--size:2.0171880242244375rem; --distance:9.351092956718778rem; --position:75.88733529539284%; --time:2.1351189300680566s; --delay:-3.0381310354377775s;"></div>
    <div class="bubble" style="--size:4.083678962520782rem; --distance:9.628254660319886rem; --position:92.11191166186197%; --time:2.8994155909781654s; --delay:-2.5580939376473335s;"></div>
    <div class="bubble" style="--size:5.956912976596767rem; --distance:7.335413261735383rem; --position:1.264498498375107%; --time:2.628991549325143s; --delay:-3.2661098650322984s;"></div>
    <div class="bubble" style="--size:2.9134918357438324rem; --distance:7.5826602281533955rem; --position:94.21445358987152%; --time:3.14376087023677s; --delay:-2.727306492080528s;"></div>
    <div class="bubble" style="--size:4.403502654827923rem; --distance:9.11270404485177rem; --position:29.759818708403422%; --time:2.0665035713325564s; --delay:-2.06397953998529s;"></div>
    <div class="bubble" style="--size:2.906565795489487rem; --distance:7.845750855035635rem; --position:94.56794936368978%; --time:2.576604210832153s; --delay:-2.8742198185075187s;"></div>
    <div class="bubble" style="--size:5.140995335291592rem; --distance:6.651277403448014rem; --position:52.27134046512927%; --time:3.3892315083612647s; --delay:-3.5242588132504977s;"></div>
    <div class="bubble" style="--size:3.342282482557604rem; --distance:7.638773554916936rem; --position:45.14532612840712%; --time:2.5969675563112196s; --delay:-2.247654531507693s;"></div>
    <div class="bubble" style="--size:3.627945598136149rem; --distance:9.276500833703269rem; --position:54.47862826247148%; --time:2.9824110006222444s; --delay:-2.847016937509765s;"></div>
    <div class="bubble" style="--size:4.155469235389571rem; --distance:8.541932908828114rem; --position:51.38219385181293%; --time:2.4657903448060363s; --delay:-2.089664627583015s;"></div>
    <div class="bubble" style="--size:3.5206965318355437rem; --distance:6.526582383591035rem; --position:104.18556377576262%; --time:3.072177469264939s; --delay:-3.149541385536881s;"></div>
    <div class="bubble" style="--size:3.169620246233265rem; --distance:8.714737570367337rem; --position:86.01048899340155%; --time:3.588590715826979s; --delay:-2.908466935410474s;"></div>
    <div class="bubble" style="--size:3.231633022339511rem; --distance:7.463451470195533rem; --position:17.770045320999696%; --time:2.04642651352213s; --delay:-3.031520346056807s;"></div>
    <div class="bubble" style="--size:4.5802988129432025rem; --distance:6.1010059263527rem; --position:57.910482117104216%; --time:3.2852118196634987s; --delay:-2.2576753243180567s;"></div>
    <div class="bubble" style="--size:4.614365588015923rem; --distance:6.778823247772166rem; --position:49.4520723693325%; --time:3.1385563827690817s; --delay:-2.130425047268713s;"></div>
    <div class="bubble" style="--size:2.0907765142868766rem; --distance:9.158299208029518rem; --position:94.39875125827783%; --time:3.21823484280821s; --delay:-3.414504249235211s;"></div>
    <div class="bubble" style="--size:3.426426244124228rem; --distance:8.158109869693053rem; --position:93.95560587319312%; --time:3.331239810358727s; --delay:-2.6709835050888024s;"></div>
    <div class="bubble" style="--size:2.5815560010914673rem; --distance:8.070523692770992rem; --position:-4.78628760327218%; --time:3.141458820111022s; --delay:-3.611787328175068s;"></div>
    <div class="bubble" style="--size:4.220270405138412rem; --distance:9.38212330128282rem; --position:87.59807676275108%; --time:3.8920928763370473s; --delay:-2.3598244921309846s;"></div>
    <div class="bubble" style="--size:5.599373064476733rem; --distance:6.102305908307018rem; --position:79.32339910690679%; --time:3.5475382158827466s; --delay:-3.1441911724155336s;"></div>
    <div class="bubble" style="--size:2.1290006495856586rem; --distance:9.411217500542614rem; --position:26.656091967363654%; --time:3.7978488409501745s; --delay:-2.9709640233220105s;"></div>
    <div class="bubble" style="--size:5.937401187569774rem; --distance:6.8678143235329445rem; --position:100.58923778158221%; --time:2.56612873513513s; --delay:-3.865543940504655s;"></div>
    <div class="bubble" style="--size:4.713176564988466rem; --distance:6.693467853893009rem; --position:12.923417692551673%; --time:3.0246022902250527s; --delay:-2.441289537211569s;"></div>
    <div class="bubble" style="--size:3.1906457845840315rem; --distance:7.992387062809652rem; --position:-4.210413229279174%; --time:2.0292051697247038s; --delay:-2.124861333422108s;"></div>
    <div class="bubble" style="--size:3.730160354349863rem; --distance:9.902207654297495rem; --position:26.758117702309782%; --time:2.316070564760717s; --delay:-2.474019589905999s;"></div>
    <div class="bubble" style="--size:2.1885119825606947rem; --distance:6.245003450686032rem; --position:34.20225626558911%; --time:3.558042599809336s; --delay:-2.0032521951542095s;"></div>
    <div class="bubble" style="--size:5.633908041877492rem; --distance:7.337003803197824rem; --position:89.43506872356423%; --time:3.434696396256588s; --delay:-2.1798066240525635s;"></div>
    <div class="bubble" style="--size:2.977485181423736rem; --distance:9.651993778429013rem; --position:69.10920627726414%; --time:3.5185825026561695s; --delay:-3.2700549701570845s;"></div>
    <div class="bubble" style="--size:4.827835499228787rem; --distance:8.900913164395293rem; --position:88.31019767635117%; --time:3.920987363830795s; --delay:-3.1631694526392695s;"></div>
    <div class="bubble" style="--size:5.3047325811537664rem; --distance:8.09388218905157rem; --position:20.30413666331015%; --time:2.3446775321695137s; --delay:-3.268206005706273s;"></div>
    <div class="bubble" style="--size:3.5387759059257546rem; --distance:7.873890412402227rem; --position:0.08238266254233739%; --time:3.1718084580040595s; --delay:-2.841772703396735s;"></div>
    <div class="bubble" style="--size:5.973717938734301rem; --distance:9.706904104490492rem; --position:43.84387291607353%; --time:2.629332499059804s; --delay:-2.356382917595428s;"></div>
    <div class="bubble" style="--size:5.104127489127199rem; --distance:7.1775070465090405rem; --position:85.05322093103665%; --time:3.2693309446094196s; --delay:-2.2676333768948207s;"></div>
    <div class="bubble" style="--size:3.584296767634129rem; --distance:8.015398536133986rem; --position:104.38281624926327%; --time:3.0982381544237265s; --delay:-2.1335734575664684s;"></div>
    <div class="bubble" style="--size:5.040703636229648rem; --distance:8.182341979939853rem; --position:6.700318224997519%; --time:3.318555320655248s; --delay:-2.3690376657546715s;"></div>
    <div class="bubble" style="--size:4.748843692543591rem; --distance:8.890789833312835rem; --position:89.72066264417727%; --time:2.6283563709914324s; --delay:-3.1554834293634078s;"></div>
    <div class="bubble" style="--size:5.614911487237888rem; --distance:8.207395675188575rem; --position:96.55412846195284%; --time:3.2263270655944196s; --delay:-2.9285719685699583s;"></div>
    <div class="bubble" style="--size:3.3606270979359265rem; --distance:9.99829555956158rem; --position:84.39036442345981%; --time:2.3066189142023523s; --delay:-2.1426232460040193s;"></div>
    <div class="bubble" style="--size:5.4381949756033645rem; --distance:8.1442800852845rem; --position:9.74382840893881%; --time:2.736619270067464s; --delay:-3.1204624303959894s;"></div>
    <div class="bubble" style="--size:3.1559073263030846rem; --distance:9.369014749428693rem; --position:91.82351993075777%; --time:2.0213606750467967s; --delay:-3.1292757753888623s;"></div>
    <div class="bubble" style="--size:3.555270179563636rem; --distance:8.64942694521504rem; --position:49.54424167322034%; --time:3.6541559107894654s; --delay:-3.851727115876731s;"></div>
    <div class="bubble" style="--size:3.709908287711164rem; --distance:6.092751131241183rem; --position:96.64181731423085%; --time:3.3775028015201145s; --delay:-2.763458900865812s;"></div>
    <div class="bubble" style="--size:2.676275853004498rem; --distance:6.295244396717353rem; --position:66.66444229668114%; --time:3.880851600037694s; --delay:-3.3903361032257284s;"></div>
    <div class="bubble" style="--size:5.555598176775584rem; --distance:9.831069688506659rem; --position:91.59430351041954%; --time:2.921018084161508s; --delay:-3.9419332633582673s;"></div>
    <div class="bubble" style="--size:4.320208678085322rem; --distance:6.369611691995107rem; --position:70.29791401978329%; --time:2.454658635105516s; --delay:-3.2033255981139304s;"></div>
    <div class="bubble" style="--size:5.132235439202846rem; --distance:8.210698972331151rem; --position:60.20478552752883%; --time:3.554410692279321s; --delay:-2.975085221304287s;"></div>
    <div class="bubble" style="--size:4.821903768995138rem; --distance:7.791826286357503rem; --position:88.77125471333922%; --time:2.9254178442320784s; --delay:-2.633756716932172s;"></div>
    <div class="bubble" style="--size:4.35339753987365rem; --distance:8.82389858801722rem; --position:75.68293931094784%; --time:2.8772974846058847s; --delay:-2.030750837794897s;"></div>
    <div class="bubble" style="--size:2.7564469630943456rem; --distance:8.185101382234162rem; --position:9.593970304351197%; --time:2.704261959793791s; --delay:-3.9798650538617593s;"></div>
    <div class="bubble" style="--size:5.545338768156936rem; --distance:9.017370400262973rem; --position:6.4801278121760255%; --time:3.4475284712287264s; --delay:-3.5769758274397336s;"></div>
    <div class="bubble" style="--size:2.64692368223477rem; --distance:7.629587677923835rem; --position:72.6311870769617%; --time:2.7277894480363027s; --delay:-3.5288585092665334s;"></div>
    <div class="bubble" style="--size:4.956774010027484rem; --distance:7.349392158144964rem; --position:16.404657841647857%; --time:2.7963002094567413s; --delay:-2.9305413420352617s;"></div>
    <div class="bubble" style="--size:5.9348323730671915rem; --distance:7.135750000703767rem; --position:80.48738419651735%; --time:3.337314874783602s; --delay:-2.051533730479849s;"></div>
    <div class="bubble" style="--size:2.9508917650602395rem; --distance:6.332373825315756rem; --position:26.101171860843003%; --time:3.1970148895358133s; --delay:-3.891025570342999s;"></div>
    <div class="bubble" style="--size:4.5095303062100545rem; --distance:9.266416750880792rem; --position:72.34905995500114%; --time:3.844571445014635s; --delay:-2.8058581233415145s;"></div>
    <div class="bubble" style="--size:3.386257306810629rem; --distance:9.449783353783586rem; --position:-3.777366438906774%; --time:2.184449933949735s; --delay:-3.9170511504579064s;"></div>
    <div class="bubble" style="--size:2.1124677118956896rem; --distance:9.1068046664064rem; --position:90.91491479360998%; --time:3.4622125620441757s; --delay:-2.975064018002108s;"></div>
    <div class="bubble" style="--size:5.0316412193217275rem; --distance:7.458386519884812rem; --position:0.2832088007949771%; --time:3.015627170480326s; --delay:-3.2118059384175s;"></div>
    <div class="bubble" style="--size:2.3363340380292827rem; --distance:9.863811355583648rem; --position:25.465212477481%; --time:2.3335165913322893s; --delay:-3.0974969067068594s;"></div>
    <div class="bubble" style="--size:3.7103824128333143rem; --distance:6.363530385664126rem; --position:57.05853776038953%; --time:3.9699181689332512s; --delay:-3.5529063463227653s;"></div>
    <div class="bubble" style="--size:3.8008265714414593rem; --distance:7.406207520351193rem; --position:-0.6360188242945561%; --time:3.175582044878529s; --delay:-2.413255216115539s;"></div>
    <div class="bubble" style="--size:2.3812695698491524rem; --distance:9.562285740819819rem; --position:-2.3825392240256624%; --time:2.851424940459067s; --delay:-3.30697532102481s;"></div>
    <div class="bubble" style="--size:3.04401478931733rem; --distance:7.193220583738933rem; --position:77.99989936755443%; --time:3.058032104149767s; --delay:-2.8044970725904323s;"></div>
    <div class="bubble" style="--size:4.730361871848803rem; --distance:8.307310966192508rem; --position:-4.654284015885162%; --time:3.1998006761547906s; --delay:-3.9490435529311867s;"></div>
    <div class="bubble" style="--size:4.349728333075447rem; --distance:7.022756864114674rem; --position:16.074577732871244%; --time:3.183823891209493s; --delay:-3.4188174652274865s;"></div>
  </div>
  <div class="content">
    <div>
      {{-- 
      <div><b>Eldew</b><a href="#">Secuce</a><a href="#">Drupand</a><a href="#">Oceash</a><a href="#">Ugefe</a><a href="#">Babed</a></div>
      <div><b>Spotha</b><a href="#">Miskasa</a><a href="#">Agithe</a><a href="#">Scesha</a><a href="#">Lulle</a></div>
      <div><b>Chashakib</b><a href="#">Chogauw</a><a href="#">Phachuled</a><a href="#">Tiebeft</a><a href="#">Ocid</a><a href="#">Izom</a><a href="#">Ort</a></div>
      <div><b>Athod</b><a href="#">Pamuz</a><a href="#">Vapert</a><a href="#">Neesk</a><a href="#">Omemanen</a></div>
      --}}
    </div>
    <div>
        <a class="image" href="https://jagcweb.es/" target="_blank" 
            style="background-image: url(&quot;{{ asset('images/jagcweb.png') }}&quot;)">
        </a>
      <p>&copy; <?php echo date("Y"); ?> Jagcweb</p>
    </div>
  </div>
</div>

<style>
  .ullist{
    position: absolute; 
    right: 12%; 
    top:6%; 
  }

  .lilist{
    position: absolute; 
    right: 5%; 
    top: 26%;
  }
</style>

<svg style="position:fixed; top:100vh">
  <defs>
    <filter id="blob">
      <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
      <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="blob"></feColorMatrix>
      <!--feComposite(in="SourceGraphic" in2="blob" operator="atop") //After reviewing this after years I can't remember why I added this but it isn't necessary for the blob effect-->
    </filter>
  </defs>
</svg>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-2.1.0.min.js') }}"></script>

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('js/owl-carousel.js') }}"></script>
    <script src="{{ asset('js/accordions.js') }}"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script src="{{ asset('js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('js/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('js/imgfix.min.js') }}"></script> 
    <script src="{{ asset('js/slick.js') }}"></script> 
    <script src="{{ asset('js/lightbox.js') }}"></script> 
    <script src="{{ asset('js/isotope.js') }}"></script> 
    
    <!-- Global Init -->
    <script src="{{ asset('js/custom.js') }}"></script>

    <!--<script>
      window.addEventListener('scroll', function() {
          localStorage.setItem('scrollPosition', window.scrollY);
      });

      window.addEventListener('load', function() {
          const scrollPosition = localStorage.getItem('scrollPosition');
          if (scrollPosition) {
              window.scrollTo(0, parseInt(scrollPosition));
          }
      });
    </script>-->

    <!-- Cookie Consent Banner -->
    <div id="cookie-consent-banner" style="position: fixed; bottom: 0; left: 0; right: 0; background-color: rgba(0,0,0,0.9); color: white; padding: 20px; display: none; z-index: 999999;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-0 fs-5" style="font-size: 14px;">
                        Utilizamos cookies para mejorar su experiencia en nuestro sitio web. Al continuar navegando, acepta nuestra 
                        <a href="#" style="color: #F65807;">política de cookies</a>.
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <button id="accept-cookies" class="btn" style="background-color: #F65807; color: white;">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
      

    <script>
        $(document).ready(function() {
            // Cookie consent handling
            function setCookie(name, value, days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
            }

            function getCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            // Show banner if consent cookie is not set
            if (!getCookie('cookie-consent')) {
                $('#cookie-consent-banner').fadeIn();
            }

            // Handle accept button click
            $('#accept-cookies').click(function() {
                setCookie('cookie-consent', 'accepted', 365);
                $('#cookie-consent-banner').fadeOut();
            });

            // Original document.ready code
            $(".btn-input2").click(function(){
                $("#login").modal('toggle');
                $("#register").modal('toggle');
            });

            $('img, video, .gallery-image, .gallery-item').on('contextmenu', function(event) {
                event.preventDefault();
            });

            // Search

            const $container = $('.search-container');
            const $input = $('.search-input');
            const $toggle = $('.search-toggle');

            // Solo ocultamos el input si no hay búsqueda activa
            if (!$input.val()) {
                $input.addClass('d-none');
            } else {
                // Si hay búsqueda, mostramos el input con el ancho correcto
                $input.css('width', '200px').css('opacity', 1);
            }

            $toggle.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                if ($input.hasClass('d-none')) {
                    $input.removeClass('d-none')
                        .css('opacity', 0)
                        .animate({
                            width: '200px',
                            opacity: 1
                        }, 300, () => {
                            $input.focus();
                        });
                }
            });

            $input.on('keydown', (e) => {
                if (e.key === 'Escape') {
                    cerrarBusqueda();
                } else if (e.key === 'Enter' && $input.val().trim() !== '') {
                    console.log('Buscando:', $input.val());
                }
            });

            $input.on('click', (e) => {
                e.stopPropagation();
            });

            $(document).on('click', () => {
                if ($input.val().trim() === '') {
                    cerrarBusqueda();
                }
            });

            const cerrarBusqueda = () => {
                if (!$input.hasClass('d-none')) {
                    $input.animate({
                        width: '0',
                        opacity: 0
                    }, 300, () => {
                        $input.val('').addClass('d-none');
                    });
                }
            };

            $('.city-dropdown li').on('click', function() {
                const cityName = $(this).text().trim().toLowerCase();
                $('.selected_city').val(cityName);
                $('.formsubmit').submit();
            });

            function toggleMenu(icon, menu) {
                $(icon).click(function (e) {
                    e.stopPropagation();
                    $(menu).toggle();
                });

                $(document).click(function (e) {
                    if (!$(e.target).closest(icon + ', ' + menu).length) {
                        $(menu).hide();
                    }
                });
            }

            toggleMenu('#bell-icon', '#bell-menu');
            toggleMenu('#envelope-icon', '#envelope-menu');
            toggleMenu('#user-icon', '#user-menu');
        });
    </script>
</body>
</html>
