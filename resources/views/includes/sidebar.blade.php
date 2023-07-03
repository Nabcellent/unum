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
            <ul x-data="{ activeDropdown: 'dashboard' }"
                class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold">
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="group">
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
                                :class="{'active' : activeDropdown === 'marks'}"
                                @click="activeDropdown === 'marks' ? activeDropdown = null : activeDropdown = 'marks'"
                            >
                                <div class="flex items-center">
                                    <svg width="24" class="group-hover:!text-primary" height="24" viewBox="0 0 24 24"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                              d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                                              fill="#1C274C"/>
                                        <path
                                            d="M10.5431 7.51724C10.8288 7.2173 10.8172 6.74256 10.5172 6.4569C10.2173 6.17123 9.74256 6.18281 9.4569 6.48276L7.14286 8.9125L6.5431 8.28276C6.25744 7.98281 5.78271 7.97123 5.48276 8.2569C5.18281 8.54256 5.17123 9.01729 5.4569 9.31724L6.59976 10.5172C6.74131 10.6659 6.9376 10.75 7.14286 10.75C7.34812 10.75 7.5444 10.6659 7.68596 10.5172L10.5431 7.51724Z"
                                            fill="#1C274C"/>
                                        <path
                                            d="M13 8.25C12.5858 8.25 12.25 8.58579 12.25 9C12.25 9.41422 12.5858 9.75 13 9.75H18C18.4142 9.75 18.75 9.41422 18.75 9C18.75 8.58579 18.4142 8.25 18 8.25H13Z"
                                            fill="#1C274C"/>
                                        <path
                                            d="M10.5431 14.5172C10.8288 14.2173 10.8172 13.7426 10.5172 13.4569C10.2173 13.1712 9.74256 13.1828 9.4569 13.4828L7.14286 15.9125L6.5431 15.2828C6.25744 14.9828 5.78271 14.9712 5.48276 15.2569C5.18281 15.5426 5.17123 16.0173 5.4569 16.3172L6.59976 17.5172C6.74131 17.6659 6.9376 17.75 7.14286 17.75C7.34812 17.75 7.5444 17.6659 7.68596 17.5172L10.5431 14.5172Z"
                                            fill="#1C274C"/>
                                        <path
                                            d="M13 15.25C12.5858 15.25 12.25 15.5858 12.25 16C12.25 16.4142 12.5858 16.75 13 16.75H18C18.4142 16.75 18.75 16.4142 18.75 16C18.75 15.5858 18.4142 15.25 18 15.25H13Z"
                                            fill="#1C274C"/>
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Mark Entry</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'marks'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'marks'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="{{ route('admin.assessment.subject') }}">Per Subject</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.assessment.student') }}">Per Student</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.attendances') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M8.04832 2.48826C8.33094 2.79108 8.31458 3.26567 8.01176 3.54829L3.72605 7.54829C3.57393 7.69027 3.36967 7.76267 3.1621 7.74818C2.95453 7.7337 2.7623 7.63363 2.63138 7.4719L1.41709 5.9719C1.15647 5.64996 1.20618 5.17769 1.52813 4.91707C1.85007 4.65645 2.32234 4.70616 2.58296 5.0281L3.29089 5.90261L6.98829 2.45171C7.2911 2.16909 7.76569 2.18545 8.04832 2.48826ZM11.25 5C11.25 4.58579 11.5858 4.25 12 4.25H22C22.4142 4.25 22.75 4.58579 22.75 5C22.75 5.41422 22.4142 5.75 22 5.75H12C11.5858 5.75 11.25 5.41422 11.25 5ZM8.04832 16.4883C8.33094 16.7911 8.31458 17.2657 8.01176 17.5483L3.72605 21.5483C3.57393 21.6903 3.36967 21.7627 3.1621 21.7482C2.95453 21.7337 2.7623 21.6336 2.63138 21.4719L1.41709 19.9719C1.15647 19.65 1.20618 19.1777 1.52813 18.9171C1.85007 18.6564 2.32234 18.7062 2.58296 19.0281L3.29089 19.9026L6.98829 16.4517C7.2911 16.1691 7.76569 16.1855 8.04832 16.4883ZM11.25 19C11.25 18.5858 11.5858 18.25 12 18.25H22C22.4142 18.25 22.75 18.5858 22.75 19C22.75 19.4142 22.4142 19.75 22 19.75H12C11.5858 19.75 11.25 19.4142 11.25 19Z"
                                              fill="#1C274C"/>
                                        <g opacity="0.5">
                                            <path
                                                d="M8.04832 9.48826C8.33094 9.79108 8.31458 10.2657 8.01176 10.5483L3.72605 14.5483C3.57393 14.6903 3.36967 14.7627 3.1621 14.7482C2.95453 14.7337 2.7623 14.6336 2.63138 14.4719L1.41709 12.9719C1.15647 12.65 1.20618 12.1777 1.52813 11.9171C1.85007 11.6564 2.32234 11.7062 2.58296 12.0281L3.29089 12.9026L6.98829 9.45171C7.2911 9.16909 7.76569 9.18545 8.04832 9.48826Z"
                                                fill="#1C274C"/>
                                            <path
                                                d="M11.25 12C11.25 11.5858 11.5858 11.25 12 11.25H22C22.4142 11.25 22.75 11.5858 22.75 12C22.75 12.4142 22.4142 12.75 22 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12Z"
                                                fill="#1C274C"/>
                                        </g>
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Attendance</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                              d="M12 20.0283V18H8L8 20.0283C8 20.3054 8 20.444 8.09485 20.5C8.18971 20.556 8.31943 20.494 8.57888 20.3701L9.82112 19.7766C9.9089 19.7347 9.95279 19.7138 10 19.7138C10.0472 19.7138 10.0911 19.7347 10.1789 19.7767L11.4211 20.3701C11.6806 20.494 11.8103 20.556 11.9051 20.5C12 20.444 12 20.3054 12 20.0283Z"
                                              fill="#1C274D"/>
                                        <path
                                            d="M8 18H7.42598C6.34236 18 5.96352 18.0057 5.67321 18.0681C5.15982 18.1785 4.71351 18.4151 4.38811 18.7347C4.27837 18.8425 4.22351 18.8964 4.09696 19.2397C3.97041 19.5831 3.99045 19.7288 4.03053 20.02C4.03761 20.0714 4.04522 20.1216 4.05343 20.1706C4.16271 20.8228 4.36259 21.1682 4.66916 21.4142C4.97573 21.6602 5.40616 21.8206 6.21896 21.9083C7.05566 21.9986 8.1646 22 9.75461 22H14.1854C15.7754 22 16.8844 21.9986 17.7211 21.9083C18.5339 21.8206 18.9643 21.6602 19.2709 21.4142C19.5774 21.1682 19.7773 20.8228 19.8866 20.1706C19.9784 19.6228 19.9965 18.9296 20 18H12V20.0283C12 20.3054 12 20.444 11.9051 20.5C11.8103 20.556 11.6806 20.494 11.4211 20.3701L10.1789 19.7767C10.0911 19.7347 10.0472 19.7138 10 19.7138C9.95279 19.7138 9.9089 19.7347 9.82112 19.7766L8.57888 20.3701C8.31943 20.494 8.18971 20.556 8.09485 20.5C8 20.444 8 20.3054 8 20.0283V18Z"
                                            fill="#1C274D"/>
                                        <path opacity="0.5"
                                              d="M4.72718 2.73332C5.03258 2.42535 5.46135 2.22456 6.27103 2.11478C7.10452 2.00177 8.2092 2 9.7931 2H14.2069C15.7908 2 16.8955 2.00177 17.729 2.11478C18.5387 2.22456 18.9674 2.42535 19.2728 2.73332C19.5782 3.0413 19.7773 3.47368 19.8862 4.2902C19.9982 5.13073 20 6.24474 20 7.84202L20 18H7.42598C6.34236 18 5.96352 18.0057 5.67321 18.0681C5.15982 18.1785 4.71351 18.4151 4.38811 18.7347C4.27837 18.8425 4.22351 18.8964 4.09696 19.2397C4.02435 19.4367 4 19.5687 4 19.7003V7.84202C4 6.24474 4.00176 5.13073 4.11382 4.2902C4.22268 3.47368 4.42179 3.0413 4.72718 2.73332Z"
                                              fill="#1C274D"/>
                                        <path
                                            d="M7.25 7C7.25 6.58579 7.58579 6.25 8 6.25H16C16.4142 6.25 16.75 6.58579 16.75 7C16.75 7.41421 16.4142 7.75 16 7.75H8C7.58579 7.75 7.25 7.41421 7.25 7Z"
                                            fill="#1C274D"/>
                                        <path
                                            d="M8 9.75C7.58579 9.75 7.25 10.0858 7.25 10.5C7.25 10.9142 7.58579 11.25 8 11.25H13C13.4142 11.25 13.75 10.9142 13.75 10.5C13.75 10.0858 13.4142 9.75 13 9.75H8Z"
                                            fill="#1C274D"/>
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Reports</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.summaries') }}" class="group">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M14 20.5V4.25C14 3.52169 13.9984 3.05091 13.9518 2.70403C13.908 2.37872 13.8374 2.27676 13.7803 2.21967C13.7232 2.16258 13.6213 2.09197 13.296 2.04823C12.9491 2.00159 12.4783 2 11.75 2C11.0217 2 10.5509 2.00159 10.204 2.04823C9.87872 2.09197 9.77676 2.16258 9.71967 2.21967C9.66258 2.27676 9.59197 2.37872 9.54823 2.70403C9.50159 3.05091 9.5 3.52169 9.5 4.25V20.5H14Z"
                                              fill="#1C274C"/>
                                        <path opacity="0.7"
                                              d="M8 8.75C8 8.33579 7.66421 8 7.25 8H4.25C3.83579 8 3.5 8.33579 3.5 8.75V20.5H8V8.75Z"
                                              fill="#1C274C"/>
                                        <path opacity="0.7"
                                              d="M20 13.75C20 13.3358 19.6642 13 19.25 13H16.25C15.8358 13 15.5 13.3358 15.5 13.75V20.5H20V13.75Z"
                                              fill="#1C274C"/>
                                        <path opacity="0.5"
                                              d="M1.75 20.5C1.33579 20.5 1 20.8358 1 21.25C1 21.6642 1.33579 22 1.75 22H21.75C22.1642 22 22.5 21.6642 22.5 21.25C22.5 20.8358 22.1642 20.5 21.75 20.5H21.5H20H15.5H14H9.5H8H3.5H2H1.75Z"
                                              fill="#1C274C"/>
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Summaries</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>System</span>
                </h2>

                <li class="nav-item">
                    <a href="{{ route('admin.classes') }}" class="group">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M2 21.25C1.58579 21.25 1.25 21.5858 1.25 22C1.25 22.4142 1.58579 22.75 2 22.75H22C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25H21H18.5H17V16C17 14.1144 17 13.1716 16.4142 12.5858C15.8284 12 14.8856 12 13 12H11C9.11438 12 8.17157 12 7.58579 12.5858C7 13.1716 7 14.1144 7 16V21.25H5.5H3H2ZM9.25 15C9.25 14.5858 9.58579 14.25 10 14.25H14C14.4142 14.25 14.75 14.5858 14.75 15C14.75 15.4142 14.4142 15.75 14 15.75H10C9.58579 15.75 9.25 15.4142 9.25 15ZM9.25 18C9.25 17.5858 9.58579 17.25 10 17.25H14C14.4142 17.25 14.75 17.5858 14.75 18C14.75 18.4142 14.4142 18.75 14 18.75H10C9.58579 18.75 9.25 18.4142 9.25 18Z"
                                      fill="#1C274C"/>
                                <g opacity="0.5">
                                    <path
                                        d="M8 4.5C8.94281 4.5 9.41421 4.5 9.70711 4.79289C10 5.08579 10 5.55719 10 6.5L9.99999 8.29243C10.1568 8.36863 10.2931 8.46469 10.4142 8.58579C10.8183 8.98987 10.9436 9.56385 10.9825 10.5V12C9.10855 12 8.16976 12.0018 7.58579 12.5858C7 13.1716 7 14.1144 7 16V21.25H3V12C3 10.1144 3 9.17157 3.58579 8.58579C3.70688 8.46469 3.84322 8.36864 4 8.29243V6.5C4 5.55719 4 5.08579 4.29289 4.79289C4.58579 4.5 5.05719 4.5 6 4.5H6.25V3C6.25 2.58579 6.58579 2.25 7 2.25C7.41421 2.25 7.75 2.58579 7.75 3V4.5H8Z"
                                        fill="#1C274C"/>
                                    <path
                                        d="M20.6439 5.24676C20.2877 4.73284 19.66 4.49743 18.4045 4.02663C15.9493 3.10592 14.7216 2.64555 13.8608 3.2421C13 3.83864 13 5.14974 13 7.77195V12C14.8856 12 15.8284 12 16.4142 12.5858C17 13.1716 17 14.1144 17 16V21.25H21V7.77195C21 6.4311 21 5.76068 20.6439 5.24676Z"
                                        fill="#1C274C"/>
                                </g>
                            </svg>

                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Classes</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.students') }}" class="group">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.217 3.49965C12.796 2.83345 11.2035 2.83345 9.78252 3.49965L5.48919 5.51246C6.27114 5.59683 6.98602 6.0894 7.31789 6.86377C7.80739 8.00594 7.2783 9.32867 6.13613 9.81817L5.06046 10.2792C4.52594 10.5082 4.22261 10.6406 4.01782 10.7456C4.0167 10.7619 4.01564 10.7788 4.01465 10.7962L9.78261 13.5003C11.2036 14.1665 12.7961 14.1665 14.2171 13.5003L20.9082 10.3634C22.3637 9.68105 22.3637 7.31899 20.9082 6.63664L14.217 3.49965Z"
                                    fill="#1C274D"/>
                                <path
                                    d="M4.9998 12.9147V16.6254C4.9998 17.6334 5.50331 18.5772 6.38514 19.0656C7.85351 19.8787 10.2038 21 11.9998 21C13.7958 21 16.1461 19.8787 17.6145 19.0656C18.4963 18.5772 18.9998 17.6334 18.9998 16.6254V12.9148L14.8538 14.8585C13.0294 15.7138 10.9703 15.7138 9.14588 14.8585L4.9998 12.9147Z"
                                    fill="#1C274D"/>
                                <path
                                    d="M5.54544 8.43955C5.92616 8.27638 6.10253 7.83547 5.93936 7.45475C5.7762 7.07403 5.33529 6.89767 4.95456 7.06083L3.84318 7.53714C3.28571 7.77603 2.81328 7.97849 2.44254 8.18705C2.04805 8.40898 1.70851 8.66944 1.45419 9.05513C1.19986 9.44083 1.09421 9.85551 1.04563 10.3055C0.999964 10.7284 0.999981 11.2424 1 11.8489V14.7502C1 15.1644 1.33579 15.5002 1.75 15.5002C2.16422 15.5002 2.5 15.1644 2.5 14.7502V11.8878C2.5 11.232 2.50101 10.7995 2.53696 10.4665C2.57095 10.1517 2.63046 9.99612 2.70645 9.88087C2.78244 9.76562 2.90202 9.64964 3.178 9.49438C3.46985 9.33019 3.867 9.15889 4.46976 8.90056L5.54544 8.43955Z"
                                    fill="#1C274D"/>
                            </svg>

                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Students</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/reports" class="group">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M14.2502 4.47954L14.2502 7.5372C14.25 7.64842 14.2498 7.80699 14.271 7.9431C14.297 8.10951 14.3826 8.43079 14.7153 8.62611C15.0357 8.81422 15.349 8.74436 15.498 8.69806C15.6278 8.6577 15.7702 8.58988 15.8764 8.5393L17.0002 8.00545L18.124 8.5393C18.2302 8.58987 18.3725 8.6577 18.5024 8.69806C18.6513 8.74436 18.9647 8.81422 19.2851 8.62611C19.6177 8.43079 19.7033 8.10952 19.7293 7.9431C19.7506 7.807 19.7504 7.64845 19.7502 7.53723L19.7502 3.0313C19.863 3.026 19.9737 3.02152 20.082 3.01775C21.1538 2.98041 22 3.86075 22 4.93319V16.1436C22 17.2546 21.094 18.1535 19.9851 18.2228C19.0157 18.2835 17.8767 18.402 17 18.6334C15.9185 18.9187 14.6271 19.5365 13.6276 20.0692C13.3485 20.218 13.0531 20.3257 12.7502 20.3925V5.17387C13.0709 5.0953 13.3824 4.97142 13.6738 4.80275C13.8581 4.6961 14.0514 4.58732 14.2502 4.47954ZM19.7278 12.8181C19.8282 13.2199 19.5839 13.6271 19.1821 13.7276L15.1821 14.7276C14.7802 14.8281 14.373 14.5837 14.2726 14.1819C14.1721 13.7801 14.4164 13.3729 14.8183 13.2724L18.8183 12.2724C19.2201 12.1719 19.6273 12.4163 19.7278 12.8181Z"
                                      fill="#1C274D"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.2502 5.21397C10.9159 5.15048 10.5894 5.03785 10.2823 4.87546C9.29611 4.35401 8.04921 3.76431 7 3.48744C6.11349 3.25351 4.95877 3.1349 3.9824 3.07489C2.8863 3.00752 2 3.89963 2 4.9978V16.1436C2 17.2546 2.90605 18.1535 4.01486 18.2228C4.98428 18.2835 6.12329 18.402 7 18.6334C8.08145 18.9187 9.37293 19.5365 10.3724 20.0692C10.6516 20.218 10.9472 20.3258 11.2502 20.3926V5.21397ZM4.27257 8.8181C4.37303 8.41625 4.78023 8.17193 5.18208 8.27239L9.18208 9.27239C9.58393 9.37285 9.82825 9.78006 9.72778 10.1819C9.62732 10.5837 9.22012 10.8281 8.81828 10.7276L4.81828 9.72761C4.41643 9.62715 4.17211 9.21994 4.27257 8.8181ZM5.18208 12.2724C4.78023 12.1719 4.37303 12.4163 4.27257 12.8181C4.17211 13.2199 4.41643 13.6271 4.81828 13.7276L8.81828 14.7276C9.22012 14.8281 9.62732 14.5837 9.72778 14.1819C9.82825 13.7801 9.58393 13.3729 9.18208 13.2724L5.18208 12.2724Z"
                                      fill="#1C274D"/>
                                <path
                                    d="M18.2502 3.15101C17.6301 3.22431 17.0206 3.33159 16.5 3.48744C16.2585 3.55975 16.0064 3.65141 15.7502 3.7564V3.95002V6.93859L16.4995 6.58266L16.5083 6.57822C16.5573 6.55316 16.7638 6.44757 17.0002 6.44757C17.0477 6.44757 17.094 6.45184 17.1381 6.45887C17.3132 6.48679 17.4529 6.5582 17.4921 6.57822L17.5009 6.58265L18.2502 6.93859V3.64665V3.15101Z"
                                    fill="#1C274D"/>
                            </svg>

                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Subjects</span>
                        </div>
                    </a>
                </li>
                <li class="menu nav-item">
                    <button
                        type="button"
                        class="nav-link group"
                        :class="{'active' : activeDropdown === 'users'}"
                        @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'"
                    >
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.5 7.5C15.5 9.433 13.933 11 12 11C10.067 11 8.5 9.433 8.5 7.5C8.5 5.567 10.067 4 12 4C13.933 4 15.5 5.567 15.5 7.5Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M18 16.5C18 18.433 15.3137 20 12 20C8.68629 20 6 18.433 6 16.5C6 14.567 8.68629 13 12 13C15.3137 13 18 14.567 18 16.5Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M7.12205 5C7.29951 5 7.47276 5.01741 7.64005 5.05056C7.23249 5.77446 7 6.61008 7 7.5C7 8.36825 7.22131 9.18482 7.61059 9.89636C7.45245 9.92583 7.28912 9.94126 7.12205 9.94126C5.70763 9.94126 4.56102 8.83512 4.56102 7.47063C4.56102 6.10614 5.70763 5 7.12205 5Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M5.44734 18.986C4.87942 18.3071 4.5 17.474 4.5 16.5C4.5 15.5558 4.85657 14.744 5.39578 14.0767C3.4911 14.2245 2 15.2662 2 16.5294C2 17.8044 3.5173 18.8538 5.44734 18.986Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M16.9999 7.5C16.9999 8.36825 16.7786 9.18482 16.3893 9.89636C16.5475 9.92583 16.7108 9.94126 16.8779 9.94126C18.2923 9.94126 19.4389 8.83512 19.4389 7.47063C19.4389 6.10614 18.2923 5 16.8779 5C16.7004 5 16.5272 5.01741 16.3599 5.05056C16.7674 5.77446 16.9999 6.61008 16.9999 7.5Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M18.5526 18.986C20.4826 18.8538 21.9999 17.8044 21.9999 16.5294C21.9999 15.2662 20.5088 14.2245 18.6041 14.0767C19.1433 14.744 19.4999 15.5558 19.4999 16.5C19.4999 17.474 19.1205 18.3071 18.5526 18.986Z"
                                    fill="#1C274C"/>
                            </svg>

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
                    <span>User & Settings</span>
                </h2>

                <li class="menu nav-item">
                    <button
                        type="button"
                        class="nav-link group"
                        :class="{'active' : activeDropdown === 'user'}"
                        @click="activeDropdown === 'user' ? activeDropdown = null : activeDropdown = 'user'"
                    >
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="6" r="4" fill="#1C274C"/>
                                <ellipse cx="12" cy="17" rx="7" ry="4" fill="#1C274C"/>
                            </svg>

                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">User</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'users'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'user'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('admin.settings', 'term') }}">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings', 'system') }}">Account Settings</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="group">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.25 14C10.9069 14 12.25 15.3431 12.25 17C12.25 18.6569 10.9069 20 9.25 20C7.59315 20 6.25 18.6569 6.25 17C6.25 15.3431 7.59315 14 9.25 14Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M14.25 4C12.5931 4 11.25 5.34315 11.25 7C11.25 8.65685 12.5931 10 14.25 10C15.9069 10 17.25 8.65685 17.25 7C17.25 5.34315 15.9069 4 14.25 4Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M8.75 6.20852C9.16421 6.20852 9.5 6.54431 9.5 6.95852C9.5 7.37273 9.16421 7.70852 8.75 7.70852L1.75 7.70852C1.33579 7.70852 1 7.37273 1 6.95852C1 6.54431 1.33579 6.20852 1.75 6.20852H8.75Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M14.75 16.2085C14.3358 16.2085 14 16.5443 14 16.9585C14 17.3727 14.3358 17.7085 14.75 17.7085H21.75C22.1642 17.7085 22.5 17.3727 22.5 16.9585C22.5 16.5443 22.1642 16.2085 21.75 16.2085H14.75Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M1 16.9585C1 16.5443 1.33579 16.2085 1.75 16.2085H3.75C4.16421 16.2085 4.5 16.5443 4.5 16.9585C4.5 17.3727 4.16421 17.7085 3.75 17.7085H1.75C1.33579 17.7085 1 17.3727 1 16.9585Z"
                                    fill="#1C274C"/>
                                <path
                                    d="M21.75 6.20852C22.1642 6.20852 22.5 6.54431 22.5 6.95852C22.5 7.37273 22.1642 7.70852 21.75 7.70852L19.75 7.70852C19.3358 7.70852 19 7.37273 19 6.95852C19 6.54431 19.3358 6.20852 19.75 6.20852H21.75Z"
                                    fill="#1C274C"/>
                            </svg>

                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Settings</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
