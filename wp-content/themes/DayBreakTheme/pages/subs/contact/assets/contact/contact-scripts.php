<script>
  (function() {
    function createToast(message, type = "success", duration = 6000) {
      const isError = type === "error";
      const safeMessage = message || (isError ? "入力内容をご確認ください。" : "送信が完了しました。");

      const toast = document.createElement("div");
      toast.className = `cf7-toast ${type}`;
      toast.setAttribute("role", isError ? "alert" : "status");
      toast.setAttribute("aria-live", isError ? "assertive" : "polite");
      toast.setAttribute("aria-atomic", "true");
      toast.setAttribute("tabindex", "-1");

      toast.innerHTML = `
        <button class="cf7-toast-close" aria-label="通知を閉じる"></button>
        <div class="cf7-toast-message">${safeMessage}</div>
        ${!isError ? '<div class="cf7-toast-progress" aria-hidden="true"></div>' : ''}
      `;

      document.body.appendChild(toast);
      requestAnimationFrame(() => toast.classList.add("show"));

      const closeBtn = toast.querySelector(".cf7-toast-close");
      if (closeBtn) closeBtn.focus();

      let timer;
      let remaining = duration;
      let startTime;

      const remove = () => {
        toast.classList.remove("show");
        setTimeout(() => {
          if (toast.parentNode) toast.remove();
        }, 300);
        document.removeEventListener("keydown", escHandler);
      };

      const startTimer = () => {
        startTime = Date.now();
        timer = setTimeout(remove, remaining);
      };

      const pauseTimer = () => {
        if (isError) return;
        clearTimeout(timer);
        remaining -= Date.now() - startTime;
      };

      const resumeTimer = () => {
        if (isError) return;
        startTimer();
      };

      if (!isError) {
        startTimer();

        const progress = toast.querySelector(".cf7-toast-progress");
        const prefersReducedMotion =
          window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        if (progress && !prefersReducedMotion) {
          progress.animate(
            [{
              transform: "scaleX(1)"
            }, {
              transform: "scaleX(0)"
            }], {
              duration: duration,
              easing: "linear"
            }
          );
        }

        toast.addEventListener("mouseenter", pauseTimer);
        toast.addEventListener("mouseleave", resumeTimer);
        toast.addEventListener("focusin", pauseTimer);
        toast.addEventListener("focusout", resumeTimer);
      }

      function escHandler(e) {
        if (e.key === "Escape") {
          clearTimeout(timer);
          remove();
        }
      }

      document.addEventListener("keydown", escHandler);

      if (closeBtn) {
        closeBtn.addEventListener("click", () => {
          clearTimeout(timer);
          remove();
        });
      }
    }

    function getCf7Message(e, fallback) {
      return e?.detail?.apiResponse?.message || fallback;
    }

    document.addEventListener("wpcf7mailsent", (e) => {
      createToast(getCf7Message(e, "送信が完了しました。"), "success");
    });

    document.addEventListener("wpcf7invalid", (e) => {
      createToast(getCf7Message(e, "入力内容をご確認ください。"), "error");
    });

    document.addEventListener("wpcf7mailfailed", (e) => {
      createToast(getCf7Message(e, "送信に失敗しました。"), "error");
    });
  })();
</script>

<script>
  (function() {
    function patchForm(form) {
      if (!form) return;

      form.querySelectorAll(".wpcf7-form-control-wrap").forEach(function(wrap) {
        const field = wrap.querySelector(
          'input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), textarea, select'
        );
        if (!field) return;

        const name = field.getAttribute("name");
        if (!name) return;

        const helpId = name + "-help";
        const currentValue = field.getAttribute("aria-describedby") || "";

        if (!document.getElementById(helpId)) return;

        const ids = currentValue.split(/\s+/).filter(Boolean);

        if (!ids.includes(helpId)) {
          field.setAttribute("aria-describedby", [helpId, ...ids].join(" "));
        }
      });
    }

    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".wpcf7 form, form.wpcf7-form").forEach(patchForm);
    });

    document.addEventListener("wpcf7invalid", function(e) {
      patchForm(e.target);
    });

    document.addEventListener("wpcf7mailfailed", function(e) {
      patchForm(e.target);
    });
  })();
</script>

<script>
  (function() {
    function patchTip(tip) {
      var wrap = tip.closest(".wpcf7-form-control-wrap");
      if (!wrap) return;

      tip.removeAttribute("aria-hidden");
      tip.setAttribute("role", "alert");

      var input = wrap.querySelector("input:not([type='hidden']), select, textarea");
      if (!input) return;
      input.setAttribute("aria-invalid", "true");

      var name = input.getAttribute("name");
      if (name) {
        tip.id = "error-" + name;
        var desc = input.getAttribute("aria-describedby") || "";
        var ids = desc.split(/\s+/).filter(Boolean);
        if (!ids.includes(tip.id)) {
          input.setAttribute("aria-describedby", [tip.id].concat(ids).join(" "));
        }
      }
    }

    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        mutation.addedNodes.forEach(function(node) {
          if (node.nodeType !== 1) return;
          if (node.classList.contains("wpcf7-not-valid-tip")) {
            patchTip(node);
          }
        });
        if (
          mutation.type === "attributes" &&
          mutation.target.classList.contains("wpcf7-not-valid-tip") &&
          mutation.target.getAttribute("aria-hidden") === "true"
        ) {
          patchTip(mutation.target);
        }
      });
    });

    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".wpcf7-form").forEach(function(form) {
        observer.observe(form, {
          childList: true,
          subtree: true,
          attributes: true,
          attributeFilter: ["aria-hidden"]
        });
      });
    });

    ["wpcf7mailsent", "wpcf7spam", "wpcf7mailfailed"].forEach(function(name) {
      document.addEventListener(name, function(e) {
        e.target.querySelectorAll("[aria-invalid]").forEach(function(el) {
          el.removeAttribute("aria-invalid");
          var ids = (el.getAttribute("aria-describedby") || "")
            .split(/\s+/).filter(function(id) {
              return id && !id.startsWith("error-");
            });
          if (ids.length > 0) {
            el.setAttribute("aria-describedby", ids.join(" "));
          } else {
            el.removeAttribute("aria-describedby");
          }
        });
      });
    });
  })();
</script>