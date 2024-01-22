@extends('layouts.app')
@section('page-name', 'General')
@section('page-parent', 'Dashboard')
@section('page-content')
    @php
        $currentYear = date('Y');
        $startYear = $currentYear - 10;
        $endYear = $currentYear + 10;
        $currentWeekNumber = date('W');
    @endphp
    <div id="kt_app_content_container" class="app-container container-xxl">
        {{-- <div class="row g-5 g-xl-10 mb-xl-10">
            <div class="col-12 col-md-3">
                <div class="card card-flush h-md-100" style="background-color: #e9e9ea">
                    <div class="card-header pt-5">
                        <h3 class="card-title text-gray-700">Highlights</h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2 d-flex align-items-center">
                                <i class="ki-outline ki-people fs-2 text-info me-2"></i>
                                Active Services
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-700 fw-bolder fs-6">{{ number_format($activeServices) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2 d-flex align-items-center">
                                <i class="ki-outline ki-profile-user fs-2 text-info me-2"></i>
                                Active Customers
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-700 fw-bolder fs-6">{{ number_format($activeCustomers) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2 d-flex align-items-center">
                                <i class="ki-outline ki-profile-user fs-2 text-info me-2"></i>
                                Total Professionals
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-700 fw-bolder fs-6">{{ number_format($totalProfessionals) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2 d-flex align-items-center">
                                <i class="ki-outline ki-office-bag fs-2 text-info me-2"></i>
                                Unresolved Disputes
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-700 fw-bolder fs-6">{{ number_format($unresolvedDisputes) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2 d-flex align-items-center">
                                <i class="ki-outline ki-badge fs-2 text-info me-2"></i>
                                Unresolved Reports
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-700 fw-bolder fs-6">{{ number_format($unresolvedReports) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-flush h-md-100" style="background-color: #080655">
                    <div class="card-header pt-5">
                        <h3 class="card-title text-white d-flex align-items-center">
                            <i class="ki-outline ki-dollar fs-2 text-white me-2"></i>
                            Transactions
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Total Transactions
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">GHS
                                    {{ number_format($totalTrans, 2) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Total Transactions Today
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">GHS
                                    {{ number_format($todayTrans, 2) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Weekly Transactions
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">GHS
                                    {{ number_format($weeklyTrans, 2) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Monthly Transactions
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">GHS
                                    {{ number_format($monthlyTrans, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-flush h-md-100" style="background-color: #06554e">
                    <div class="card-header pt-5">
                        <h3 class="card-title text-white d-flex align-items-center">
                            <i class="ki-outline ki-route fs-2 text-white me-2"></i>
                            Completed Tasks
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Total Completed Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($totalCptTasks) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Today Completed Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($todayCptTasks) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Weekly Completed Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($weeklyCptTasks) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Monthly Completed Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($monthlyCptTasks) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-flush h-md-100" style="background-color: #701d2a">
                    <div class="card-header pt-5 d-flex align-items-center">
                        <h3 class="card-title text-white d-flex align-items-center">
                            <i class="ki-outline ki-route fs-2 text-white me-2"></i>
                            Cancelled Tasks
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Total Cancelled Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($totalCldTasks) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Today Cancelled Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($todayCldTasks) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Weekly Cancelled Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($weeklyCldTasks) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-white fw-semibold fs-6 me-2 d-flex align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-white me-3"></div>
                                Monthly Cancelled Tasks
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bolder fs-6">{{ number_format($monthlyCldTasks) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-10 mb-xl-10">
            <div class="col-12 mb-5 mb-xl-0">
                <div class="card h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Tasks Today</span>

                            <span class="text-muted mt-1 fw-semibold fs-7">Total
                                {{ number_format(count($totalTasksToday)) }} tasks today</span>
                        </h3>

                        <div class="card-toolbar">
                            <a href="{{ route('tasks') }}" class="btn btn-sm btn-light">Tasks</a>
                        </div>
                    </div>
                    <div class="card-body pt-7 px-0">
                        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true"
                            data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-offset="100px">
                            @if (count($totalTasksToday) > 0)
                                @foreach ($totalTasksToday as $taskToday)
                                    <div class="d-flex align-items-center mb-6">
                                        <span data-kt-element="bullet"
                                            class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 
                                               @if ($taskToday->status == 'ACTIVE') bg-primary
                                               @elseif ($taskToday->status == 'PENDING')
                                               bg-warning
                                               @elseif ($taskToday->status == 'CLOSED')
                                               bg-info
                                               @elseif ($taskToday->status == 'CANCELLED')
                                               bg-danger
                                               @elseif ($taskToday->status == 'COMPLETED')
                                               bg-success
                                               @elseif ($taskToday->status == 'DELETED')
                                               bg-secondary @endif">
                                        </span>
                                        <div class="flex-grow-1 me-5">
                                            <div class="text-gray-800 fw-semibold fs-2">
                                                {{ $taskToday->code }}
                                                <span class="text-gray-400 fw-semibold fs-7">
                                                    {{ $taskToday->status }}
                                                </span>
                                            </div>

                                            <div class="text-gray-700 fw-semibold fs-6">
                                                {{ $taskToday->customer_name ? $taskToday->customer_name : $taskToday->customer->fname . ($taskToday->customer->mname ? ' ' . $taskToday->customer->mname . ' ' : ' ') . $taskToday->customer->lname }}
                                                (Customer) requested service(s) from {{ $taskToday->business_name ? $taskToday->business_name : $taskToday->professional->business_name }} (Professional)
                                            </div>

                                            <div class="text-gray-400 fw-semibold fs-7">
                                                Requested on
                                                <a href="#" class="text-primary opacity-75-hover fw-semibold">
                                                    {{ strtoupper(date('j M, Y h:i:a', strtotime($taskToday->created_at))) }}</a>
                                            </div>
                                        </div>
                                        <a href="{{ route('tasks.view', ['id' => $taskToday->id]) }}"
                                            class="btn btn-sm btn-light">View</a>
                                    </div>
                                @endforeach
                            @else
                                <div class="mb-2">
                                    <h1 class="fw-semibold text-gray-800 text-center lh-lg mt-3">
                                        No tasks <br> requested today
                                    </h1>

                                    <div class="py-10 text-center">
                                        <img src="{{ asset('assets/images/1.svg') }}" class="theme-light-show w-400px"
                                            alt="">
                                        <img src="{{ asset('assets/images/1.svg') }}" class="theme-dark-show w-400px"
                                            alt="">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-10 mb-xl-10">
            <div class="col-12 mb-5 mb-xl-0">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Leading Professionals</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Top 10 performing professionals</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="px-2 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                    <thead>
                                        <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                            <th class="p-0"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 w-50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($leadingProfessionals) > 0)
                                            @foreach ($leadingProfessionals as $leadingProfessional)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-60px me-3">
                                                                <img src="{{ $leadingProfessional->display_picture ?? asset('assets/images/user.png') }}"
                                                                    class="img-card" alt="">
                                                            </div>

                                                            <div class="d-flex justify-content-start flex-column">
                                                                <a href="{{ route('professionals.view', ['id' => $leadingProfessional->id]) }}"
                                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $leadingProfessional->business_name }}</a>
                                                                <span
                                                                    class="text-muted fw-semibold d-block fs-7">{{ $leadingProfessional->userid }}</span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->whereIn('status',['ACTIVE','CLOSED','COMPLETED','ACTIVE','PENDING','CANCELLED']))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Total
                                                            Tasks</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->where('status', 'ACTIVE'))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Active
                                                            Tasks</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->where('status', 'CLOSED'))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Closed
                                                            Tasks</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->where('status', 'COMPLETED'))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Completed
                                                            Tasks</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->where('status', 'ACTIVE'))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Active Tasks</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->where('status', 'PENDING'))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Pending
                                                            Tasks</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold d-block mb-1 fs-6">{{ number_format(count($leadingProfessional->tasks->where('status', 'CANCELLED'))) }}</span>
                                                        <span class="fw-semibold text-gray-400 d-block">Cancelled
                                                            Tasks</span>
                                                    </td>

                                                    <td>
                                                        <div class="rating">
                                                            @for ($index = 0; $index < 5; $index++)
                                                                <div
                                                                    class="rating-label me-2 {{ $index < $leadingProfessional->rating ? 'checked' : '' }}">
                                                                    <i
                                                                        class="ki-outline ki-star fs-3"></i>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </td>

                                                    <td class="text-end">
                                                        <a href="{{ route('professionals.view', ['id' => $leadingProfessional->id]) }}"
                                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                            <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <div class="d-flex flex-center flex-column">
                                                <img src="{{ asset('assets/images/5.svg') }}" width="300px"
                                                    class="mt-5" alt="">
                                                <span class="fs-2 fw-bold mt-4">No records found</span>
                                            </div>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-5">
                <form id="weekly-form" class="form">
                    @csrf
                    <div class="card card-custom gutter-b card-stretch">
                        <div class="card-header border-0 pt-5">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="me-3">
                                        Weekly Transactions
                                    </span>
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                                    <select name="year" id="weekly-year" class="form-control me-3">
                                        @for ($year = $startYear; $year <= $endYear; $year++)
                                            @php $selected = ($year == $currentYear) ? 'selected' : ''; @endphp
                                            <option value="{{ $year }}" {{ $selected }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="week_number" id="weekly-week" class="form-control w-100px me-3">
                                        @for ($week = 1; $week <= 52; $week++)
                                            @php $selected = ($week == $currentWeekNumber) ? 'selected' : ''; @endphp
                                            <option value="{{ $week }}" {{ $selected }}>Week
                                                {{ $week }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="loading text-primary fw-bold" style="display: none;">
                                Loading...
                            </div>
                            <canvas id="weekly-chart" class="mh-400px"></canvas>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 mb-5">
                <form id="monthly-form">
                    @csrf
                    <div class="card card-custom gutter-b card-stretch">
                        <div class="card-header border-0 pt-5">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="me-3">
                                        Monthly Transactions
                                    </span>
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                                    <select name="year" id="monthly-year" class="form-control w-100px me-3">
                                        @for ($year = $startYear; $year <= $endYear; $year++)
                                            @php $selected = ($year == $currentYear) ? 'selected' : ''; @endphp
                                            <option value="{{ $year }}" {{ $selected }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="loading text-primary fw-bold" style="display: none;">
                                Loading...
                            </div>
                            <canvas id="monthly-chart" class="mh-400px"></canvas>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>
@endsection
@push('js-scripts')
    {{-- @vite(['resources/js/dashboard/index.js']) --}}
@endpush
