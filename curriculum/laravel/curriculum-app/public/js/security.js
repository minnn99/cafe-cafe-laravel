// XSS対策とフォームバリデーション（問い合わせフォーム専用）
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contactForm"); // 問い合わせフォームのみ対象

    if (form) {
        // XSS攻撃に使用される可能性のある文字パターン
        const xssPatterns = [
            /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
            /<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
            /<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/gi,
            /<embed\b[^<]*>/gi,
            /<form\b[^<]*(?:(?!<\/form>)<[^<]*)*<\/form>/gi,
            /javascript:/gi,
            /vbscript:/gi,
            /onload=/gi,
            /onclick=/gi,
            /onmouseover=/gi,
            /onerror=/gi,
            /alert\s*\(/gi,
            /document\.cookie/gi,
            /document\.write/gi,
            /eval\s*\(/gi,
        ];

        // 入力フィールドをリアルタイムで検証
        const inputs = form.querySelectorAll("input, textarea");
        inputs.forEach((input) => {
            input.addEventListener("input", function () {
                validateInput(this);
            });

            input.addEventListener("paste", function (e) {
                // ペースト後に少し遅延して検証
                setTimeout(() => {
                    validateInput(this);
                }, 10);
            });
        });

        // フォーム送信時の最終検証
        form.addEventListener("submit", function (e) {
            let hasXSSAttempt = false;

            inputs.forEach((input) => {
                if (containsXSS(input.value)) {
                    hasXSSAttempt = true;
                    showError(
                        input,
                        "セキュリティ上の問題により、この入力は受け付けられません。",
                    );
                }
            });

            if (hasXSSAttempt) {
                e.preventDefault();
                alert(
                    "入力内容にセキュリティ上の問題があります。確認してください。",
                );
                return false;
            }
        });

        function validateInput(input) {
            const value = input.value;
            const errorElement = document.getElementById(input.name + "-error");

            if (containsXSS(value)) {
                showError(
                    input,
                    "この入力には使用できない文字が含まれています。",
                );
                return false;
            } else {
                hideError(input);
                return true;
            }
        }

        function containsXSS(value) {
            if (!value) return false;

            // XSSパターンをチェック
            for (let pattern of xssPatterns) {
                if (pattern.test(value)) {
                    return true;
                }
            }

            // 危険な文字をチェック
            const dangerousChars = ["<", ">", '"', "'", "&"];
            for (let char of dangerousChars) {
                if (value.includes(char)) {
                    return true;
                }
            }

            return false;
        }

        function showError(input, message) {
            const errorElement = document.getElementById(input.name + "-error");
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.add("show");
            }
            input.classList.add("error");
        }

        function hideError(input) {
            const errorElement = document.getElementById(input.name + "-error");
            if (errorElement) {
                errorElement.textContent = "";
                errorElement.classList.remove("show");
            }
            input.classList.remove("error");
        }
    }
});

// グローバルなXSS対策（すべてのフォームに適用）
(function () {
    "use strict";

    // HTMLエスケープ関数
    window.escapeHtml = function (text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    };

    // 安全なinnerHTMLの代替
    window.safeSetHTML = function (element, content) {
        element.textContent = content;
    };

    // CSRFトークンを取得する関数
    window.getCSRFToken = function () {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute("content") : "";
    };
})();
