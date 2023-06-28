<div :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav
        x-data="sidebar"
        class="sidebar fixed top-0 bottom-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300"
    >
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="/" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none" src="{{ asset('/images/logo.jpg') }}" alt="image"/>
                    <span
                        class="align-middle text-xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">STRATHMORE</span>
                </a>
                <a
                    href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()"
                >
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"/>
                        <path
                            opacity="0.5"
                            d="M16.9998 19L10.9998 12L16.9998 5"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </a>
            </div>
            <ul
                class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: 'dashboard' }"
            >
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="/" class="group">
                                <div class="flex items-center">
                                    <svg
                                        class="group-hover:!text-primary"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            opacity="0.5"
                                            d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg
                        class="hidden h-5 w-4 flex-none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Assessment</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group"
                                :class="{'active' : activeDropdown === 'users'}"
                                @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'"
                            >
                                <div class="flex items-center">
                                    <i class="fa-solid fa-list-ol group-hover:!text-primary"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Marks Entry</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'users'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'users'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="{{ route('admin.assessment.subject') }}">Per Subject</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.assessment.student') }}">Per Student</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/reports" class="group">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-list-ol group-hover:!text-primary"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Attendance</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports') }}" class="group">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-list-ol group-hover:!text-primary"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Reports</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/summaries" class="group">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-list-ol group-hover:!text-primary"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Summaries</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>User & Settings</span>
                </h2>

                <li class="menu nav-item">
                    <button
                        type="button"
                        class="nav-link group"
                        :class="{'active' : activeDropdown === 'users'}"
                        @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'"
                    >
                        <div class="flex items-center">
                            <i class="fa-solid fa-users group-hover:!text-primary"></i>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Users</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'users'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'users'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="users-profile.html">Profile</a>
                        </li>
                        <li>
                            <a href="users-account-settings.html">Account Settings</a>
                        </li>
                    </ul>
                </li>

                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>System</span>
                </h2>

                <li class="nav-item">
                    <a href="/reports" class="group">
                        <div class="flex items-center">
                            <i class="fa-solid fa-building-user group-hover:!text-primary"></i>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Classes</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/reports" class="group">
                        <div class="flex items-center">
                            <i class="fa-solid fa-graduation-cap group-hover:!text-primary"></i>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Students</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/reports" class="group">
                        <div class="flex items-center">
                            <i class="fa-solid fa-book group-hover:!text-primary"></i>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Subjects</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
