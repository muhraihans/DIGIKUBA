"use strict";

(function () {
  var sidebarStorageKey = "adminHMD.sidebarMini";
  var themeStorageKey = "adminHMD.colorTheme";
  var desktopMedia = "(min-width: 992px)";

  function onReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
      return;
    }

    callback();
  }

  function isDesktop() {
    return window.matchMedia(desktopMedia).matches;
  }

  function canUseStorage() {
    try {
      var testKey = sidebarStorageKey + ".test";
      window.localStorage.setItem(testKey, "1");
      window.localStorage.removeItem(testKey);
      return true;
    } catch (error) {
      return false;
    }
  }

  function getSavedMiniState(storageAvailable) {
    if (!storageAvailable) {
      return false;
    }

    return window.localStorage.getItem(sidebarStorageKey) === "true";
  }

  function saveMiniState(storageAvailable, isMini) {
    if (storageAvailable) {
      window.localStorage.setItem(sidebarStorageKey, String(isMini));
    }
  }

  function getPreferredTheme(storageAvailable) {
    var savedTheme = storageAvailable ? window.localStorage.getItem(themeStorageKey) : "";

    if (savedTheme === "dark" || savedTheme === "light") {
      return savedTheme;
    }

    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
      return "dark";
    }

    return "light";
  }

  onReady(function () {
    var body = document.body;
    var sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    var themeToggles = document.querySelectorAll("[data-theme-toggle]");
    var themeIcons = document.querySelectorAll("[data-theme-icon]");
    var closeButtons = document.querySelectorAll("[data-sidebar-close]");
    var sidebarLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    var mediaQuery = window.matchMedia(desktopMedia);
    var storageAvailable = canUseStorage();

    function initValidation() {
      var forms = document.querySelectorAll(".needs-validation");

      Array.prototype.forEach.call(forms, function (form) {
        form.addEventListener("submit", function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add("was-validated");
        });
      });
    }

    function initTableSearch() {
      var monthNames = {
        januari: 1, februari: 2, maret: 3, april: 4, mei: 5, juni: 6,
        juli: 7, agustus: 8, september: 9, oktober: 10, november: 11, desember: 12
      };

      function parseDate(value) {
        var text = String(value || "").trim().toLowerCase();
        var match = text.match(/^(\d{4})[-/](\d{1,2})[-/](\d{1,2})(?:[t\s]+(\d{1,2}):(\d{2})(?::(\d{2}))?)?/);

        if (match) {
          return new Date(+match[1], +match[2] - 1, +match[3], +(match[4] || 0), +(match[5] || 0), +(match[6] || 0)).getTime();
        }

        match = text.match(/^(\d{1,2})[./-](\d{1,2})[./-](\d{4})(?:\s+(\d{1,2}):(\d{2})(?::(\d{2}))?)?/);

        if (match) {
          return new Date(+match[3], +match[2] - 1, +match[1], +(match[4] || 0), +(match[5] || 0), +(match[6] || 0)).getTime();
        }

        match = text.match(/^(\d{1,2})\s+([a-z]+)\s+(\d{4})(?:\s+(\d{1,2}):(\d{2}))?/);

        if (match && monthNames[match[2]]) {
          return new Date(+match[3], monthNames[match[2]] - 1, +match[1], +(match[4] || 0), +(match[5] || 0)).getTime();
        }

        var parsed = Date.parse(text);
        return isNaN(parsed) ? null : parsed;
      }

      function cellText(row, index) {
        var cell = row.cells[index];
        return cell ? cell.textContent.replace(/\s+/g, " ").trim() : "";
      }

      function parseCreatedAt(row, dateColumn) {
        var timestamp = Number(row.getAttribute("data-created-at"));

        if (isFinite(timestamp) && timestamp > 0) {
          return timestamp * 1000;
        }

        return dateColumn >= 0 ? parseDate(cellText(row, dateColumn)) : null;
      }

      function isDateHeader(label) {
        return /created|dibuat|tanggal|waktu|\bdate\b/i.test(label);
      }

      Array.prototype.forEach.call(document.querySelectorAll("table"), function (table) {
        if (table.dataset.tableEnhanced === "true" || table.matches(".identitas, .kop-table, .signature-table, [data-table-static]")) {
          return;
        }

        var responsive = table.closest(".table-responsive");

        if (!responsive) {
          responsive = document.createElement("div");
          responsive.className = "table-responsive";
          table.parentNode.insertBefore(responsive, table);
          responsive.appendChild(table);
        }

        var headerRow = table.tHead && table.tHead.rows.length ? table.tHead.rows[0] : null;
        var tbody = table.tBodies.length ? table.tBodies[0] : null;

        if (!headerRow || !tbody || !headerRow.cells.length) {
          return;
        }

        var rows = Array.prototype.filter.call(tbody.rows, function (row, index) {
          if (row.querySelector("td[colspan]")) {
            return false;
          }

          row._tableOriginalIndex = index;
          return true;
        });

        table.dataset.tableEnhanced = "true";

        if (!rows.length) {
          return;
        }

        var headers = Array.prototype.map.call(headerRow.cells, function (cell) {
          return cell.textContent.replace(/\s+/g, " ").trim();
        });
        var dateColumn = headers.findIndex(function (label) {
          return /created|dibuat|tanggal( pengajuan| daftar)?$|waktu|\bdate\b/i.test(label);
        });
        var sortableDateColumn = headers.findIndex(isDateHeader);
        var sortDateColumn = dateColumn >= 0 ? dateColumn : sortableDateColumn;
        var numberColumn = /^(no\.?|#)$/i.test(headers[0]) ? 0 : -1;
        var toolbar = document.createElement("div");
        toolbar.className = "table-toolbar d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3";

        var summary = document.createElement("small");
        summary.className = "table-result-count text-muted";
        toolbar.appendChild(summary);

        var search = document.createElement("input");
        search.type = "search";
        search.className = "form-control form-control-sm table-search-input";
        search.placeholder = "Cari di tabel...";
        search.setAttribute("aria-label", "Cari data dalam tabel");
        search.style.maxWidth = "320px";
        toolbar.appendChild(search);
        responsive.parentNode.insertBefore(toolbar, responsive);

        var noResultsRow = document.createElement("tr");
        noResultsRow.hidden = true;
        noResultsRow.setAttribute("data-table-no-results", "true");
        var noResultsCell = document.createElement("td");
        noResultsCell.colSpan = headers.length;
        noResultsCell.className = "text-center text-muted py-4";
        noResultsCell.textContent = "Tidak ada data yang cocok dengan pencarian.";
        noResultsRow.appendChild(noResultsCell);
        tbody.appendChild(noResultsRow);

        function updateRowNumbers() {
          if (numberColumn < 0) {
            return;
          }

          var visibleIndex = 0;
          rows.forEach(function (row) {
            if (row.hidden) {
              return;
            }

            visibleIndex += 1;
            if (row.cells[numberColumn]) {
              row.cells[numberColumn].textContent = String(visibleIndex);
            }
          });
        }

        function updateSearch() {
          var query = search.value.trim().toLocaleLowerCase();
          var visibleCount = 0;

          rows.forEach(function (row) {
            var matches = query === "" || row.textContent.toLocaleLowerCase().indexOf(query) !== -1;
            row.hidden = !matches;
            if (matches) {
              visibleCount += 1;
            }
          });

          noResultsRow.hidden = visibleCount !== 0;
          summary.textContent = "Menampilkan " + visibleCount + " dari " + rows.length + " baris pada halaman ini";
          updateRowNumbers();
        }

        function setSortIcon(activeColumn, direction) {
          Array.prototype.forEach.call(headerRow.querySelectorAll(".table-sort-icon"), function (icon) {
            var button = icon.closest("button");
            var column = button ? Number(button.getAttribute("data-sort-column")) : -1;
            icon.className = "table-sort-icon";
            icon.textContent = column === activeColumn
              ? (direction === 1 ? "↑" : "↓")
              : "↕";
          });
        }

        function sortRows(column, direction, byCreatedAt) {
          var sorted = rows.slice().sort(function (a, b) {
            var left;
            var right;

            if (byCreatedAt) {
              left = parseCreatedAt(a, sortDateColumn);
              right = parseCreatedAt(b, sortDateColumn);
              left = left === null ? 0 : left;
              right = right === null ? 0 : right;
            } else if (isDateHeader(headers[column])) {
              left = parseDate(cellText(a, column));
              right = parseDate(cellText(b, column));
              left = left === null ? 0 : left;
              right = right === null ? 0 : right;
            } else {
              left = cellText(a, column);
              right = cellText(b, column);

              if (column === numberColumn) {
                left = Number(left) || 0;
                right = Number(right) || 0;
              } else {
                left = left.toLocaleLowerCase();
                right = right.toLocaleLowerCase();
                var result = left.localeCompare(right, "id", { numeric: true, sensitivity: "base" });
                return result === 0 ? a._tableOriginalIndex - b._tableOriginalIndex : result * direction;
              }
            }

            return left === right ? a._tableOriginalIndex - b._tableOriginalIndex : (left - right) * direction;
          });

          var fragment = document.createDocumentFragment();
          sorted.forEach(function (row) {
            fragment.appendChild(row);
          });
          fragment.appendChild(noResultsRow);
          tbody.appendChild(fragment);
        }

        Array.prototype.forEach.call(headerRow.cells, function (cell, index) {
          var label = headers[index];

          if (!label || /^(aksi|action)$/i.test(label)) {
            return;
          }

          var button = document.createElement("button");
          button.type = "button";
          button.className = "btn btn-link table-sort-button p-0 text-decoration-none fw-semibold";
          button.setAttribute("data-sort-column", String(index));
          button.setAttribute("aria-label", "Urutkan berdasarkan " + label);

          var labelElement = document.createElement("span");
          labelElement.textContent = label;
          var icon = document.createElement("i");
          icon.className = "table-sort-icon ms-2";
          icon.textContent = "↕";
          icon.setAttribute("aria-hidden", "true");
          button.appendChild(labelElement);
          button.appendChild(icon);

          while (cell.firstChild) {
            cell.removeChild(cell.firstChild);
          }
          cell.appendChild(button);
          cell.setAttribute("aria-sort", "none");

          button.addEventListener("click", function () {
            var currentColumn = Number(button.getAttribute("data-sort-column"));
            var currentDirection = button.getAttribute("data-sort-direction") === "asc" ? -1 : 1;

            Array.prototype.forEach.call(headerRow.querySelectorAll("button[data-sort-column]"), function (otherButton) {
              otherButton.removeAttribute("data-sort-direction");
              otherButton.parentNode.setAttribute("aria-sort", "none");
            });

            button.setAttribute("data-sort-direction", currentDirection === 1 ? "asc" : "desc");
            cell.setAttribute("aria-sort", currentDirection === 1 ? "ascending" : "descending");
            setSortIcon(currentColumn, currentDirection);
            sortRows(currentColumn, currentDirection, false);
            updateSearch();
          });
        });

        search.addEventListener("input", updateSearch);
        sortRows(sortDateColumn, -1, true);
        if (sortableDateColumn >= 0 && sortableDateColumn === dateColumn) {
          var defaultDateButton = headerRow.querySelector('button[data-sort-column="' + sortableDateColumn + '"]');
          if (defaultDateButton) {
            defaultDateButton.setAttribute("data-sort-direction", "desc");
            defaultDateButton.parentNode.setAttribute("aria-sort", "descending");
            setSortIcon(sortableDateColumn, -1);
          }
        }
        updateSearch();
      });

      var legacyInputs = document.querySelectorAll("[data-table-search]");
      Array.prototype.forEach.call(legacyInputs, function (input) {
        var table = document.getElementById(input.getAttribute("data-table-search"));
        if (!table || table.dataset.tableEnhanced === "true") {
          return;
        }

        input.addEventListener("input", function () {
          var query = input.value.trim().toLocaleLowerCase();
          Array.prototype.forEach.call(table.querySelectorAll("tbody tr"), function (row) {
            row.hidden = query !== "" && row.textContent.toLocaleLowerCase().indexOf(query) === -1;
          });
        });
      });
    }

    function updateThemeControls(theme) {
      var nextTheme = theme === "dark" ? "light" : "dark";
      var label = "Switch to " + nextTheme + " mode";
      var iconClass = theme === "dark" ? "bi bi-sun" : "bi bi-moon-stars";

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.setAttribute("aria-label", label);
        button.setAttribute("title", label);
      });

      Array.prototype.forEach.call(themeIcons, function (icon) {
        icon.className = iconClass;
      });
    }

    function applyTheme(theme) {
      document.documentElement.setAttribute("data-theme", theme);
      document.documentElement.setAttribute("data-bs-theme", theme);

      if (storageAvailable) {
        window.localStorage.setItem(themeStorageKey, theme);
      }

      updateThemeControls(theme);
    }

    function initThemeToggle() {
      applyTheme(getPreferredTheme(storageAvailable));

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.addEventListener("click", function () {
          var currentTheme = document.documentElement.getAttribute("data-theme") === "dark" ? "dark" : "light";
          applyTheme(currentTheme === "dark" ? "light" : "dark");
        });
      });
    }

    if (document.documentElement.hasAttribute("data-table-tools-only")) {
      initTableSearch();
      return;
    }

    initValidation();
    initTableSearch();
    initThemeToggle();

    // Initialize user profile values in UI. Provide a window.adminHMDUser object to override defaults.
    function initUserProfile() {
      var user = window.adminHMDUser || { name: "Admin Hasan", workspace: "Active Workspace", avatar: "../assets/images/avatar/avatar.jpg" };

      var sidebarNameEl = document.querySelector(".sidebar-user strong");
      var sidebarWorkspaceEl = document.querySelector(".sidebar-user small");
      var sidebarAvatar = document.querySelector(".sidebar-user .avatar-img");
      var profileNameEls = document.querySelectorAll(".profile-name");
      var profileAvatarEls = document.querySelectorAll(".profile-button .avatar-img, .profile-button img");

      if (sidebarNameEl) sidebarNameEl.textContent = user.name;
      if (sidebarWorkspaceEl) sidebarWorkspaceEl.textContent = user.workspace;
      if (sidebarAvatar && user.avatar) { sidebarAvatar.src = user.avatar; sidebarAvatar.alt = user.name; }

      Array.prototype.forEach.call(profileNameEls, function (el) { el.textContent = user.name; });
      Array.prototype.forEach.call(profileAvatarEls, function (img) { if (user.avatar) img.src = user.avatar; if (user.name) img.alt = user.name; });
    }

    initUserProfile();

    if (!sidebarToggle) {
      return;
    }

    function setClass(element, className, enabled) {
      if (enabled) {
        element.classList.add(className);
      } else {
        element.classList.remove(className);
      }
    }

    function setToggleExpanded() {
      var expanded = isDesktop()
        ? !body.classList.contains("sidebar-mini")
        : body.classList.contains("sidebar-open");

      sidebarToggle.setAttribute("aria-expanded", String(expanded));
    }

    function closeMobileSidebar() {
      body.classList.remove("sidebar-open");
      setToggleExpanded();
    }

    function toggleSidebar() {
      if (isDesktop()) {
        body.classList.toggle("sidebar-mini");
        saveMiniState(storageAvailable, body.classList.contains("sidebar-mini"));
      } else {
        body.classList.toggle("sidebar-open");
      }

      setToggleExpanded();
    }

    function addCloseHandlers(items) {
      Array.prototype.forEach.call(items, function (item) {
        item.addEventListener("click", function () {
          if (!isDesktop()) {
            closeMobileSidebar();
          }
        });
      });
    }

    if (getSavedMiniState(storageAvailable) && isDesktop()) {
      body.classList.add("sidebar-mini");
    }

    sidebarToggle.addEventListener("click", toggleSidebar);
    addCloseHandlers(closeButtons);
    addCloseHandlers(sidebarLinks);
    setToggleExpanded();

    function handleBreakpointChange() {
      if (isDesktop()) {
        body.classList.remove("sidebar-open");
        setClass(body, "sidebar-mini", getSavedMiniState(storageAvailable));
      } else {
        body.classList.remove("sidebar-mini");
      }

      setToggleExpanded();
    }

    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener("change", handleBreakpointChange);
    } else if (mediaQuery.addListener) {
      mediaQuery.addListener(handleBreakpointChange);
    }
  });
})();
