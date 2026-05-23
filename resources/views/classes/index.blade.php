<x-app-layout>
    <x-slot name="title">Classes</x-slot>

    <style>
        .classes-kpi-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .classes-kpi-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1rem;
        }

        .classes-kpi-card > div:first-child {
            min-width: 0;
        }

        .classes-kpi-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        .class-card-actions {
            background: #f8fafc;
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
            gap: 0.45rem;
            align-items: center;
        }

        .class-card-action {
            width: 100%;
            min-width: 0;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.45rem 0.65rem;
            font-size: 0.76rem;
            font-weight: 500;
            white-space: nowrap;
            line-height: 1.1;
        }

        .class-card-action--secondary {
            border-color: rgba(34, 197, 94, 0.3);
            color: var(--primary-dark);
            background: #fff;
        }

        .class-card-action--attendance {
            border-color: rgba(34, 197, 94, 0.28);
            color: #16a34a;
            background: #fff;
        }

        .class-card-menu-trigger {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .class-card-menu {
            min-width: 11rem;
            padding: 0.35rem;
        }

        .class-card-menu button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.75rem;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: var(--text-main);
            font-size: 0.85rem;
            font-weight: 500;
            text-align: left;
        }

        .class-card-menu button:hover {
            background: #f3f4f6;
        }

        .class-card-menu .danger {
            color: #dc2626;
        }

        @media (max-width: 640px) {
            .classes-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.5rem;
            }
            .classes-kpi-card {
                padding: 0.75rem 0.5rem;
                flex-direction: column;
                align-items: center;
                text-align: center;
                justify-content: center;
                min-height: 110px;
            }
            .classes-kpi-card > div:first-child {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .classes-kpi-value {
                font-size: 1.25rem;
            }
            .classes-kpi-card div[style*="font-size: 0.85rem"] {
                font-size: 0.65rem !important;
                margin-bottom: 0.15rem !important;
            }
            .classes-kpi-card div[style*="font-size: 0.75rem"] {
                display: none; 
            }
            .classes-kpi-card div[style*="width: 48px"] {
                width: 32px !important;
                height: 32px !important;
                margin-bottom: 0.35rem;
                order: -1;
            }
            .classes-kpi-card div[style*="width: 48px"] i {
                width: 16px !important;
                height: 16px !important;
            }

            /* Classes Grid 1 in a row - Fixed broken layout */
            .classes-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            .classes-grid .card {
                padding: 0 !important;
                border-radius: 12px !important;
            }
            .classes-grid .card > div:first-child {
                padding: 1.25rem !important;
            }
            .classes-grid h3 {
                font-size: 1rem !important;
            }
            .classes-grid div[style*="font-size: 0.9rem"] {
                font-size: 0.8rem !important;
            }
            .classes-grid .badge {
                font-size: 0.7rem !important;
                padding: 0.15rem 0.4rem !important;
            }
            .classes-grid div[style*="margin-top: 1rem; border-top"] {
                margin-top: 1rem !important;
                padding: 0.75rem 0 !important;
                gap: 0.6rem !important;
            }
            .classes-grid div[style*="font-size: 0.85rem"] {
                font-size: 0.8rem !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.15rem !important;
            }
            .classes-grid div[style*="font-size: 0.85rem"] span:last-child {
                font-size: 0.85rem !important;
            }
            
            .class-card-actions {
                padding: 0.75rem 1rem !important;
                grid-template-columns: 1fr 1fr auto !important;
                gap: 0.4rem !important;
            }
            .class-card-action {
                padding: 0.45rem 0.6rem !important;
                font-size: 0.72rem !important;
                height: 32px !important;
            }
            .class-card-action i {
                width: 14px !important;
                height: 14px !important;
            }

            .filters-inline {
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
                gap: 0.5rem !important;
            }
            .filters-inline .filter-item { 
                flex: none !important; 
                width: 100% !important; 
                min-width: 0 !important; 
            }
            .filters-inline select { 
                font-size: 0.8rem !important; 
                height: 38px !important; 
                padding: 0 0.5rem !important; 
                width: 100% !important;
            }
            
            form[action*="classes.index"] {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.75rem !important;
                align-items: stretch !important;
            }
            form[action*="classes.index"] > div:first-child {
                width: 100% !important;
                min-width: 0 !important;
                flex: none !important;
            }
            form[action*="classes.index"] input[name="search"],
            form[action*="classes.index"] .filters-inline select,
            form[action*="classes.index"] .btn-primary,
            form[action*="classes.index"] .btn-secondary {
                height: 38px !important;
                font-size: 0.85rem !important;
                border-radius: 8px !important;
            }
            form[action*="classes.index"] .filters-inline select {
                padding: 0 0.75rem !important;
            }
            form[action*="classes.index"] > div:last-child {
                width: 100% !important;
                margin-left: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            form[action*="classes.index"] > div:last-child a,
            form[action*="classes.index"] > div:last-child button {
                width: 100% !important;
                flex: none !important;
                justify-content: center !important;
                white-space: nowrap !important;
            }
        }

        @media (min-width: 768px) {
            .classes-kpi-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.25rem;
            }

            .classes-kpi-card {
                padding: 1.25rem 1.5rem;
            }

            .classes-kpi-value {
                font-size: 2rem;
            }

            .class-card-actions {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
            }
        }
    </style>

    {{-- Dark page banner header --}}
    <x-app-banner title="My Classes">
        <x-slot name="subtitle">Manage your classes, rosters, and attendance records.</x-slot>
    </x-app-banner>

    <div class="app-page">

        @if($errors->any())
            <div class="alert alert-error">
                <strong>Please fix the errors below:</strong>
                <ul style="margin: .5rem 0 0 1.25rem; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Stat Cards / KPI Grid (Matching layout reference) --}}
        <div class="classes-kpi-grid">
            {{-- Total Classes --}}
            <div class="card classes-kpi-card">
                <div>
                    <div style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0.25rem;">Total Classes</div>
                    <div class="classes-kpi-value">{{ $totalClasses }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">{{ $totalClasses }} active &bull; 0 archived</div>
                </div>
                <div style="background: rgba(34, 197, 94, 0.1); color: var(--primary-dark); width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="book" data-size="22"></i>
                </div>
            </div>

            {{-- Total Students --}}
            <div class="card classes-kpi-card">
                <div>
                    <div style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0.25rem;">Total Students</div>
                    <div class="classes-kpi-value">{{ $totalStudents }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Enrolled across all classes</div>
                </div>
                <div style="background: rgba(236, 72, 153, 0.1); color: #ec4899; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="graduation-cap" data-size="22"></i>
                </div>
            </div>

            {{-- Avg Class Size --}}
            <div class="card classes-kpi-card">
                <div>
                    <div style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0.25rem;">Avg Class Size</div>
                    <div class="classes-kpi-value">{{ $avgClassSize }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Students per class</div>
                </div>
                <div style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="bar-chart-3" data-size="22"></i>
                </div>
            </div>

            {{-- Instructors --}}
            <div class="card classes-kpi-card">
                <div>
                    <div style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0.25rem;">Instructors</div>
                    <div class="classes-kpi-value">{{ $instructorsCount }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Active instructors</div>
                </div>
                <div style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="user-plus" data-size="22"></i>
                </div>
            </div>
        </div>

        {{-- Search and Filter Bar (Matching layout reference) --}}
        <div class="card" style="margin-bottom: 1.5rem; border-radius: 12px;">
            <div class="card-body" style="padding: 0.75rem 1.25rem;">
                <form method="GET" action="{{ route('classes.index') }}" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; width: 100%;">
                    {{-- Search --}}
                    <div style="flex: 1; min-width: 240px; position: relative;">
                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); display: flex; align-items: center;">
                            <i data-lucide="search" data-size="16"></i>
                        </span>
                        <input class="form-control" type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search classes, courses..." style="padding-left: 2.25rem; padding-right: 2.5rem; height: 38px; border-radius: 8px;">
                        @if(request('search'))
                            <a href="{{ route('classes.index', array_filter(['year' => request('year')])) }}" 
                               style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;text-decoration:none;transition:background-color 0.2s, color 0.2s;"
                               title="Clear search"
                               onmouseover="this.style.color='var(--text-main)';this.style.background='rgba(0,0,0,0.05)';"
                               onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent';"
                            >
                                <i data-lucide="x" data-size="16"></i>
                            </a>
                        @endif
                    </div>

                    <div class="filters-inline" style="display:flex; gap:0.6rem; align-items:center;">
                        {{-- Year Filter --}}
                        <div class="filter-item" style="flex: 0 0 140px; min-width: 0; width: 140px;">
                            <select class="form-control" name="year" onchange="this.form.submit()" style="width: 100%; height: 38px; border-radius: 8px; font-size: 0.85rem;">
                                <option value="All">All Years</option>
                                <option value="1" @selected(request('year') == '1')>1st Year</option>
                                <option value="2" @selected(request('year') == '2')>2nd Year</option>
                                <option value="3" @selected(request('year') == '3')>3rd Year</option>
                                <option value="4" @selected(request('year') == '4')>4th Year</option>
                            </select>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div style="display: flex; gap: 0.5rem; margin-left: auto;">
                        @if(request()->anyFilled(['search', 'year']))
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm" style="height: 38px; padding: 0 1rem; border-radius: 8px;">Clear</a>
                        @endif
                        <button
                            type="button"
                            class="btn btn-primary"
                            style="background: var(--primary); height: 38px; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.35rem; border: none; font-weight: 500; font-size: 0.85rem; padding: 0 1rem;"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-class')"
                        >
                            <i data-lucide="plus" data-size="16" style="vertical-align: middle;"></i>
                            Create Class
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Classes Grid --}}
        @if((($classes ?? collect())->isNotEmpty()))
            <div class="classes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                @foreach(($classes ?? collect()) as $c)
                    @php /** @var \App\Models\SchoolClass $c */ @endphp
                    <div class="card" style="overflow: visible; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
                        <div style="padding: 1.5rem; flex: 1;">
                            {{-- Header --}}
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: var(--primary-dark); display: inline-flex; align-items: center;">
                                            <i data-lucide="book-open" data-size="18"></i>
                                        </span>
                                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin: 0;">{{ $c->class_code }}</h3>
                                    </div>
                                    <div style="font-size: 0.9rem; font-weight: 500; color: var(--text-muted); margin-top: 0.15rem;">{{ $c->class_name }}</div>
                                </div>
                            </div>

                            {{-- Academic Year and Created Info --}}
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 0.75rem 0;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                                    <span style="color: var(--text-muted);">Academic Year</span>
                                    <span style="font-weight: 600; color: var(--text-main);">{{ $c->academic_year }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                                    <span style="color: var(--text-muted);">Program Year & Block</span>
                                    <span style="font-weight: 600; color: var(--text-main);">{{ $c->course }} - {{ $c->year }}{{ $c->block }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                                    <span style="color: var(--text-muted);">Created</span>
                                    <span style="color: var(--text-muted);">{{ $c->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>

                            {{-- Capacity and Enrollment Progress --}}
                            <div style="margin-top: 1rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.35rem;">
                                    <span style="font-weight: 500; color: var(--text-muted);">Enrollment</span>
                                    <span style="font-weight: 700; color: var(--primary-dark);">{{ $c->students_count }}/{{ $c->capacity }}</span>
                                </div>
                                @php
                                    $pct = $c->capacity > 0 ? min(100, round(($c->students_count / $c->capacity) * 100)) : 0;
                                    $barColor = $pct >= 90 ? '#ef4444' : ($pct >= 75 ? '#f59e0b' : 'var(--primary)');
                                @endphp
                                <div data-class-progress data-progress="{{ $pct }}" data-color="{{ $barColor }}" style="height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; width: 100%;">
                                    <div data-class-progress-fill style="height: 100%; border-radius: 3px; transition: width 0.3s ease;"></div>
                                </div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); text-align: right; margin-top: 0.25rem;">{{ $pct }}% capacity</div>
                            </div>

                        </div>

                        {{-- Card Footer Buttons --}}
                        <div class="class-card-actions">
                            <a href="{{ route('classes.show', ['class' => $c->id, 'tab' => 'roster']) }}" class="btn btn-secondary btn-sm class-card-action class-card-action--secondary">
                                <i data-lucide="users" data-size="14"></i>
                                Manage Roster
                            </a>
                            <a href="{{ route('classes.show', ['class' => $c->id, 'tab' => 'attendance']) }}" class="btn btn-secondary btn-sm class-card-action class-card-action--attendance">
                                <i data-lucide="calendar-days" data-size="14"></i>
                                Attendance
                            </a>
                            <x-dropdown align="right" width="48" contentClasses="class-card-menu bg-white">
                                <x-slot name="trigger">
                                    <button type="button" class="class-card-menu-trigger" aria-label="Class actions">
                                        <i data-lucide="ellipsis-vertical" data-size="18"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <button type="button" x-on:click="$dispatch('open-modal', 'edit-class-{{ $c->id }}')">
                                        <i data-lucide="edit-3" data-size="14"></i>
                                        Edit
                                    </button>
                                    <button type="button" class="danger" x-on:click="$dispatch('open-modal', 'delete-class-{{ $c->id }}')">
                                        <i data-lucide="trash-2" data-size="14"></i>
                                        Remove
                                    </button>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>

                    {{-- Edit Class Modal --}}
                    <x-modal name="edit-class-{{ $c->id }}" focusable maxWidth="lg">
                        <form method="POST" action="{{ route('classes.update', $c->id) }}" class="p-6">
                            @csrf
                            @method('PUT')
                            <div class="modern-card-header" style="margin-bottom: 1.25rem;">
                                <h2 class="modern-card-title" style="font-size: 1.1rem; font-weight: 700;">Edit Class: {{ $c->class_code }}</h2>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">Update details for this class section</div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="class_name_{{ $c->id }}">Class Name *</label>
                                    <input class="form-control" id="class_name_{{ $c->id }}" name="class_name" value="{{ old('class_name', $c->class_name) }}" required maxlength="100">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="class_code_{{ $c->id }}">Class Code *</label>
                                    <input class="form-control" id="class_code_{{ $c->id }}" name="class_code" value="{{ old('class_code', $c->class_code) }}" required maxlength="20">
                                </div>
                            </div>

                            <div class="form-row" style="margin-top: 0.5rem;">
                                <div class="form-group">
                                    <label class="form-label" for="course_{{ $c->id }}">Program *</label>
                                    <select class="form-control" id="course_{{ $c->id }}" name="course" required>
                                        <option value="BSIT" @selected(old('course', $c->course) == 'BSIT')>BSIT</option>
                                        <option value="BSEMC" @selected(old('course', $c->course) == 'BSEMC')>BSEMC</option>
                                        <option value="BSCS" @selected(old('course', $c->course) == 'BSCS')>BSCS</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="year_{{ $c->id }}">Year *</label>
                                    <select class="form-control" id="year_{{ $c->id }}" name="year" required>
                                        <option value="1" @selected(old('year', $c->year) == '1')>1st Year</option>
                                        <option value="2" @selected(old('year', $c->year) == '2')>2nd Year</option>
                                        <option value="3" @selected(old('year', $c->year) == '3')>3rd Year</option>
                                        <option value="4" @selected(old('year', $c->year) == '4')>4th Year</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row" style="margin-top: 0.5rem;">
                                <div class="form-group">
                                    <label class="form-label" for="block_{{ $c->id }}">Block *</label>
                                    <select class="form-control" id="block_{{ $c->id }}" name="block" required>
                                        @foreach(range('A','J') as $letter)
                                            <option value="{{ $letter }}" @selected(old('block', $c->block) === $letter)>{{ $letter }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="capacity_{{ $c->id }}">Max Capacity *</label>
                                    <input type="number" class="form-control" id="capacity_{{ $c->id }}" name="capacity" value="{{ old('capacity', $c->capacity) }}" min="1" max="500" required>
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; justify-content: flex-end; align-items: center;">
                                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close-modal', 'edit-class-{{ $c->id }}')">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </x-modal>

                    {{-- Delete Class Confirmation Modal --}}
                    <x-modal name="delete-class-{{ $c->id }}" focusable maxWidth="md">
                        <form method="POST" action="{{ route('classes.destroy', $c->id) }}" class="p-6">
                            @csrf
                            @method('DELETE')

                            <div class="modern-card-header" style="margin-bottom: 1rem;">
                                <h2 class="modern-card-title" style="font-size: 1.1rem; font-weight: 700; color: #111827;">Delete Class</h2>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">This action cannot be undone.</div>
                            </div>

                            <div class="card" style="padding: 1rem; border-color: rgba(239, 68, 68, 0.18); background: #fff7f7; box-shadow: none;">
                                <div style="font-size: 0.95rem; font-weight: 600; color: #991b1b; margin-bottom: 0.25rem;">{{ $c->class_code }} - {{ $c->class_name }}</div>
                                <div style="font-size: 0.85rem; color: #7f1d1d; line-height: 1.5;">
                                    Deleting this class will permanently remove the section and its related data from the system.
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; justify-content: flex-end; align-items: center;">
                                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close-modal', 'delete-class-{{ $c->id }}')">Cancel</button>
                                <button type="submit" class="btn btn-primary" style="background: #dc2626; border: none;">Delete Class</button>
                            </div>
                        </form>
                    </x-modal>
                @endforeach
            </div>
        @else
            <div class="card" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                <i data-lucide="book" data-size="48" style="margin: 0 auto 1rem; color: #cbd5e1;"></i>
                <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">No classes found</h3>
                <p style="font-size: 0.9rem; max-width: 24rem; margin: 0 auto 1.5rem;">Create a class section to manage students and track attendance.</p>
                <button type="button" class="btn btn-primary" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-class')">
                    <i data-lucide="plus" data-size="16" style="margin-right: 0.35rem; vertical-align: middle;"></i>
                    Create Class
                </button>
            </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-class-progress]').forEach((track) => {
                    const fill = track.querySelector('[data-class-progress-fill]');
                    if (!fill) return;

                    const progress = track.getAttribute('data-progress') || '0';
                    const color = track.getAttribute('data-color') || 'var(--primary)';

                    fill.style.width = `${progress}%`;
                    fill.style.background = color;
                });
            });
        </script>

        {{-- Create Class Modal --}}
        <x-modal name="create-class" focusable maxWidth="lg">
            <form method="POST" action="{{ route('classes.store') }}" class="p-6">
                @csrf
                <div class="modern-card-header" style="margin-bottom: 1.25rem;">
                    <h2 class="modern-card-title" style="font-size: 1.1rem; font-weight: 700;">Create New Class Code</h2>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Set up a new academic class section with assigned instructors</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="class_name">Class Name *</label>
                        <input class="form-control" id="class_name" name="class_name" value="{{ old('class_name') }}" placeholder="E.g. IT Elective 3 - Web/Mobile Backend Development (LAB) Management" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="class_code">Class Code *</label>
                        <input class="form-control" id="class_code" name="class_code" value="{{ old('class_code') }}" placeholder="E.g. 43455 - ITE324L" required maxlength="20">
                    </div>
                </div>

                <div class="form-row" style="margin-top: 0.5rem;">
                    <div class="form-group">
                        <label class="form-label" for="course">Program *</label>
                        <select class="form-control" id="course" name="course" required>
                            <option value="">Select Program</option>
                            <option value="BSIT" @selected(old('course') == 'BSIT')>BSIT</option>
                            <option value="BSEMC" @selected(old('course') == 'BSEMC')>BSEMC</option>
                            <option value="BSCS" @selected(old('course') == 'BSCS')>BSCS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="year">Year *</label>
                        <select class="form-control" id="year" name="year" required>
                            <option value="">Select Year</option>
                            <option value="1" @selected(old('year') == '1')>1st Year</option>
                            <option value="2" @selected(old('year') == '2')>2nd Year</option>
                            <option value="3" @selected(old('year') == '3')>3rd Year</option>
                            <option value="4" @selected(old('year') == '4')>4th Year</option>
                        </select>
                    </div>
                </div>

                <div class="form-row" style="margin-top: 0.5rem;">
                    <div class="form-group">
                        <label class="form-label" for="block">Block *</label>
                        <select class="form-control" id="block" name="block" required>
                            <option value="">Select Block</option>
                            @foreach(range('A','J') as $letter)
                                <option value="{{ $letter }}" @selected(old('block') === $letter)>{{ $letter }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="capacity">Max Capacity *</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', 40) }}" min="1" max="500" required>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; justify-content: flex-end; align-items: center;">
                    <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close-modal', 'create-class')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Class</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
