<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities" data-kt-drawer-activate="true"
    data-kt-drawer-overlay="true" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
    <div class="card shadow-none border-0 rounded-0">
        <div class="card-header" id="kt_activities_header">
            <h3 class="card-title fw-bold text-dark">Activity Logs</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
                    id="kt_activities_close">
                    <i class="ki-outline ki-cross fs-1"></i> </button>
            </div>
        </div>
        <div class="card-body position-relative" id="kt_activities_body">
            @if (count(Auth::user()->userLogs) > 0)
                <div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
                    data-kt-scroll-height="auto"
                    data-kt-scroll-wrappers="#kt_activities_body"data-kt-scroll-offset="0px">
                    <div class="timeline">
                        @foreach (Auth::user()->userLogs()->latest('created_at')->take(10)->get() as $log)
                            <div class="timeline-item">
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light">
                                        <i class="ki-outline ki-flag fs-2 text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="timeline-content mb-10 mt-n2">
                                    <div class="overflow-auto pe-3">
                                        <div class="fs-5 fw-semibold mb-2">
                                            Accessed the {{ $log->module }} module.
                                            <h6 class="fs-6 m-2">Action: {{ $log->action }}.</h6>
                                            <h6 class="fs-6 m-2">IP address: {{ $log->ipaddress }}</h6>
                                            <h6 class="fs-6 m-2">Country: {{ $log->country_name }},
                                                ({{ $log->country_code }})
                                            </h6>
                                            <h6 class="fs-6 m-2">Region: {{ $log->region_name }}
                                                ({{ $log->region_code }})</h6>
                                            <h6 class="fs-6 m-2">City:{{ $log->city_name }}</h6>
                                            <h6 class="fs-6 m-2">Zip code: {{ $log->zip_code }}</h6>
                                            <h6 class="fs-6 m-2">Longitude: {{ $log->longitude }}, Latitude:
                                                {{ $log->latitude }}</h6>
                                            <h6 class="fs-6 m-2">Request: {{ $log->request }}</h6>
                                            <h6 class="fs-6 m-2">Status: {{ $log->status }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center mt-1 fs-6">
                                            <div class="text-muted me-2 fs-7">
                                                {{ strtoupper(date('j M, Y h:i:a', strtotime($log->created_at))) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <h1 class="fw-bolder text-gray-900 mb-7">
                    No logs yet
                </h1>
            @endif
        </div>
        @if (count(Auth::user()->userLogs) > 10)
            <div class="card-footer py-5 text-center" id="kt_activities_footer">
                <a href="{{ route('profile') }}" class="btn btn-bg-body text-primary">
                    View All Activities <i class="ki-outline ki-arrow-right fs-3 text-primary"></i> </a>
            </div>
        @endif
    </div>
</div>
