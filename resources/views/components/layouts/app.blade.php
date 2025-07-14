<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sistem Presensi</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <!-- jsvectormap -->
    {{-- <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    /> --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    @livewireStyles
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <livewire:navigation.side-navigation />
      <!--end::Header-->
      <!--begin::Sidebar-->
      
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
      <livewire:navigation.navbar />
        <!--begin::App Content Header-->
        {{ $slot }}
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <strong>
          Copyright &copy; {{ date('Y') }}.&nbsp;
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->

    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
      crossorigin="anonymous"
    ></script>
    <!-- sortablejs -->
    <script>
      const connectedSortables = document.querySelectorAll('.connectedSortable');
      connectedSortables.forEach((connectedSortable) => {
        let sortable = new Sortable(connectedSortable, {
          group: 'shared',
          handle: '.card-header',
        });
      });

      const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
      cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = 'move';
      });
    </script>
    {{-- <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <!-- ChartJS -->
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options,
      );
      sales_chart.render();
    </script> --}}
    <!-- jsvectormap -->
    {{-- <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script> --}}
    <!-- jsvectormap -->
    <script>
      const visitorsData = {
        US: 398, // USA
        SA: 400, // Saudi Arabia
        CA: 1000, // Canada
        DE: 500, // Germany
        FR: 760, // France
        CN: 300, // China
        AU: 700, // Australia
        BR: 600, // Brazil
        IN: 800, // India
        GB: 320, // Great Britain
        RU: 3000, // Russia
      };

      // World map by jsVectorMap
      // const map = new jsVectorMap({
      //   selector: '#world-map',
      //   map: 'world',
      // });

      // Sparkline charts
      const option_sparkline1 = {
        series: [
          {
            data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      // const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
      // sparkline1.render();

      // const option_sparkline2 = {
      //   series: [
      //     {
      //       data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
      //     },
      //   ],
      //   chart: {
      //     type: 'area',
      //     height: 50,
      //     sparkline: {
      //       enabled: true,
      //     },
      //   },
      //   stroke: {
      //     curve: 'straight',
      //   },
      //   fill: {
      //     opacity: 0.3,
      //   },
      //   yaxis: {
      //     min: 0,
      //   },
      //   colors: ['#DCE6EC'],
      // };

      // const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
      // sparkline2.render();

      // const option_sparkline3 = {
      //   series: [
      //     {
      //       data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
      //     },
      //   ],
      //   chart: {
      //     type: 'area',
      //     height: 50,
      //     sparkline: {
      //       enabled: true,
      //     },
      //   },
      //   stroke: {
      //     curve: 'straight',
      //   },
      //   fill: {
      //     opacity: 0.3,
      //   },
      //   yaxis: {
      //     min: 0,
      //   },
      //   colors: ['#DCE6EC'],
      // };

      // const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
      // sparkline3.render();
    </script>
    <!--end::Script-->
    <script src="https://kit.fontawesome.com/800cfdc68e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- alpine.js --}}
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
      
      // $(document).ready(function () {
      //     $('#lokasiSelect').select2({
      //         dropdownParent: $('#rolePresensiModal') // tetap wajib kalau di dalam modal
      //     });
          
      //     // Dapatkan komponen Livewire aktif
      //     function getLivewireComponent() {
      //         if (
      //             typeof window.Livewire === 'undefined' ||
      //             typeof window.Livewire.components === 'undefined' ||
      //             typeof window.Livewire.components.componentsById === 'undefined'
      //         ) {
      //             return null;
      //         }

      //         const components = window.Livewire.components.componentsById;
      //         const keys = Object.keys(components);
      //         if (keys.length === 0) return null;

      //         const firstKey = keys[0];
      //         return components[firstKey] || null;
      //     }

      //     // Sinkronkan perubahan select ke Livewire
      //     document.addEventListener('livewire:load', function () {
      //         initSelect();
      //     });

      //     document.addEventListener('livewire:update', function () {
      //         initSelect();
      //     });

      //     function initSelect() {
      //         const $select = $('#lokasiSelect');
      //         if ($select.length) {
      //             $select.select2();
      //             console.log('Select2 initialized ✅');

      //             $select.off('select2:select').on('select2:select', function () {
      //                 const selectedValues = ($(this).val() || []).map(Number);
      //                 console.log('SELECTED VALUES:', selectedValues);

      //                 const wireEl = this.closest('[wire\\:id]');
      //                 const componentId = wireEl ? wireEl.getAttribute('wire:id') : null;
      //                 const livewireInstance = componentId ? Livewire.find(componentId) : null;

      //                 if (livewireInstance) {
      //                     livewireInstance.set('lokasi_presensi', selectedValues);
      //                     console.log('Dikirim ke Livewire:', selectedValues);
      //                 } else {
      //                     console.warn('Livewire instance tidak ditemukan.');
      //                 }
      //             });
      //         } else {
      //             console.warn('Select element tidak ditemukan');
      //         }
      //     }

      //     // Jika komponen Livewire update, reset value Select2
      //     window.Livewire.hook('message.processed', () => {
      //         const livewireInstance = getLivewireComponent();
      //         if (livewireInstance) {
      //             const selected = livewireInstance.get('lokasi_presensi');
      //             $('#lokasiSelect').val(selected).trigger('change');
      //         }
      //     });
      // });

      $('#rolePresensiModal').on('shown.bs.modal', function () {
          const $select = $('#lokasiSelect');

          if ($select.length) {
              $select.select2({
                  dropdownParent: $('#rolePresensiModal')
              });

              // Set nilai awal dari Livewire ke Select2
              const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
              if (livewireInstance) {
                  const selected = livewireInstance.get('lokasi_presensi');
                  $select.val(selected).trigger('change');
              }

              $select.on('change', function () {
                  const selectedValues = ($(this).val() || []).map(Number);
                  livewireInstance.set('lokasi_presensi', selectedValues);
                  console.log('Dikirim ke Livewire:', selectedValues);
              });
          }
      });

      $('#editRolePresensiModal').on('shown.bs.modal', function () {
          const $select = $('#lokasiSelect2');

          if ($select.length) {
              $select.select2({
                  dropdownParent: $('#editRolePresensiModal')
              });

              // Set nilai awal dari Livewire ke Select2
              const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
              if (livewireInstance) {
                  const selected = livewireInstance.get('lokasi_presensi');
                  $select.val(selected).trigger('change');
              }

              $select.on('change', function () {
                  const selectedValues = ($(this).val() || []).map(Number);
                  livewireInstance.set('lokasi_presensi', selectedValues);
                  console.log('Dikirim ke Livewire:', selectedValues);
              });
          }
      });

      $('#modalAdd').on('shown.bs.modal', function () {
          const $select = $('#selectJabatan');

          if ($select.length) {
              $select.select2({
                  dropdownParent: $('#modalAdd')
              });

              // Set nilai awal dari Livewire ke Select2
              const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
              if (livewireInstance) {
                  const selected = livewireInstance.get('nama_jabatan');
                  $select.val(selected).trigger('change');
              }

              $select.on('change', function () {
                  const selectedValues = ($(this).val() || []).map(Number);
                  livewireInstance.set('nama_jabatan', selectedValues);
                  console.log('Dikirim ke Livewire:', selectedValues);
              });
          }
      });


      
    </script>
    @livewireScripts
    @stack('scripts')
    @yield('scripts')

    <script>
      // Color Mode Toggler
      (() => {
        "use strict";

        const storedTheme = localStorage.getItem("theme");

        const getPreferredTheme = () => {
          if (storedTheme) {
            return storedTheme;
          }

          return window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light";
        };

        const setTheme = function (theme) {
          if (
            theme === "auto" &&
            window.matchMedia("(prefers-color-scheme: dark)").matches
          ) {
            document.documentElement.setAttribute("data-bs-theme", "dark");
          } else {
            document.documentElement.setAttribute("data-bs-theme", theme);
          }
        };

        setTheme(getPreferredTheme());

        const showActiveTheme = (theme, focus = false) => {
          const themeSwitcher = document.querySelector("#bd-theme");

          if (!themeSwitcher) {
            return;
          }

          const themeSwitcherText = document.querySelector("#bd-theme-text");
          const activeThemeIcon = document.querySelector(".theme-icon-active i");
          const btnToActive = document.querySelector(
            `[data-bs-theme-value="${theme}"]`
          );
          const svgOfActiveBtn = btnToActive.querySelector("i").getAttribute("class");

          for (const element of document.querySelectorAll("[data-bs-theme-value]")) {
            element.classList.remove("active");
            element.setAttribute("aria-pressed", "false");
          }

          btnToActive.classList.add("active");
          btnToActive.setAttribute("aria-pressed", "true");
          activeThemeIcon.setAttribute("class", svgOfActiveBtn);
          const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
          themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);

          if (focus) {
            themeSwitcher.focus();
          }
        };

        window
          .matchMedia("(prefers-color-scheme: dark)")
          .addEventListener("change", () => {
            if (storedTheme !== "light" || storedTheme !== "dark") {
              setTheme(getPreferredTheme());
            }
          });

        window.addEventListener("DOMContentLoaded", () => {
          showActiveTheme(getPreferredTheme());

          for (const toggle of document.querySelectorAll("[data-bs-theme-value]")) {
            toggle.addEventListener("click", () => {
              const theme = toggle.getAttribute("data-bs-theme-value");
              localStorage.setItem("theme", theme);
              setTheme(theme);
              showActiveTheme(theme, true);
            });
          }
        });
      })();
    </script>

    {{-- <script>
      const vapidPublicKey = "{{ config('webpush.vapid.public_key') }}";

      async function subscribeUser() {
          // Pastikan browser mendukung Service Worker
          if (!('serviceWorker' in navigator)) {
              console.error("Service Worker tidak didukung di browser ini.");
              return;
          }

          try {
              const registration = await navigator.serviceWorker.register('/sw.js');
              console.log(registration);
              
              const subscription = await registration.pushManager.subscribe({
                  userVisibleOnly: true,
                  applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
              });

              // await fetch('/api/subscriptions', {
              //     method: 'POST',
              //     headers: {
              //         'Content-Type': 'application/json',
              //         // Jika tidak pakai Sanctum, bisa kosongkan Authorization
              //         // 'Authorization': 'Bearer {{ auth()->check() ? auth()->user()->createToken("webpush")->plainTextToken : "" }}'
              //     },
              //     body: JSON.stringify(subscription)
              // });

              alert('Berhasil subscribe notifikasi!');
          } catch (e) {
              console.error("Gagal subscribe:", e);
          }
      }

      function urlBase64ToUint8Array(base64String) {
          const padding = '='.repeat((4 - base64String.length % 4) % 4);
          const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
          const rawData = window.atob(base64);
          return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
      }

      // Panggil saat halaman siap
      window.addEventListener('load', subscribeUser);
    </script> --}}

    {{-- @auth
      <script src="{{ asset('js/enable-push.js') }}" defer></script>
    @endauth --}}

    <!-- Bootstrap Datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    {{-- <script>
      $(document).ready(function() {
          $('.js-example-basic-multiple').select2();
      });
    </script> --}}
  </body>
  <!--end::Body-->
</html>
