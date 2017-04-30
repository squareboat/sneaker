<!DOCTYPE html>
<html>
    <head>
        <meta name="robots" content="noindex,nofollow" />
        <style>
            /* Copyright (c) 2010, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html */
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}

            html { background: #eee; padding: 10px }
            img { border: 0; }
            #sf-resetcontent { width:970px; margin:0 auto; }
            .extra-info {
                background-color: #FFFFFF;
                padding: 15px 28px;
                margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }

            .padding {
                padding: 15px 28px;
            }

            .extra-info .title {
                color: #4674ca;
                font-size: large;
                font-family: Monaco,monospace;
                border-bottom: 1px solid #ccc;
            }

            .tags.no-margin {
                margin-bottom: -10px;
            }
            .tags {
                padding-left: 0;
                list-style: none;
                display: flex;
                flex-wrap: wrap;
                font-size: 13px;
            }
            .tags li {
                white-space: nowrap;
                margin: 0 10px 10px 0;
                border-radius: 1px;
                display: flex;
                border: 1px solid #d0c9d7;
                border-radius: 3px;
                box-shadow: 0 1px 2px rgba(0,0,0,.04);
                line-height: 1.2;
                max-width: 100%;
            }
            .tags .key, .tags .value {
                padding: 4px 8px;
                min-width: 0;
                white-space: nowrap;
            }
            .tags .value, .tags .value>a {
                max-width: 100%;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .tags .key {
                font-family:inherit;
            }
            .tags .value {
                color: #4674ca;
                background: #fbfbfc;
                border-left: 1px solid #d8d2de;
                border-radius: 0 3px 3px 0;
                font-family: Monaco,monospace;
            }
            .tags .key, .tags .value {
                padding: 4px 8px;
                min-width: 0;
                white-space: nowrap;
            }

            {!! $report->getHtmlStylesheet() !!}
        </style>
    </head>
    <body>
        {!! $report->getHtmlContent() !!}

        @if($report->getUser())
            <div class="extra-info" style="padding: 0;">
                <div class="padding title">User</div>
                <div class="padding">
                    <div class="tags">
                        @foreach ($report->getUser() as $key => $item)
                            <li>
                                <span class="key">{{ $key }}</span>
                                <span class="value">{{ $item }}</span>
                            </li>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($report->getExtra())
            <div class="extra-info" style="padding: 0;">
                <div class="padding title">Extra Data</div>
                <div class="padding">
                    <div class="tags">
                        @foreach ($report->getExtra() as $key => $item)
                            <li>
                                <span class="key">{{ $key }}</span>
                                <span class="value">{{ $item }}</span>
                            </li>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="extra-info" style="padding: 0;">
            <div class="padding title">Request</div>
            <div class="padding">
                <div class="tags">
                    @foreach ($report->getRequest() as $key => $item)
                        <li>
                            <span class="key">{{ $key }}</span>
                            <span class="value">{{ $item }}</span>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="extra-info">
            &#128336; &nbsp;{{ $report->getTime()->format('l, jS \of F Y h:i:s a') }} {{ $report->getTime()->tzName }}
        </div>
    </body>
</html>
