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
            let errors = [];

            // 各フィールドのバリデーション（資料通り）
            const nameInput = form.querySelector('input[name="name"]');
            const kanaInput = form.querySelector('input[name="kana"]');
            const emailInput = form.querySelector('input[name="email"]');
            const bodyInput = form.querySelector('textarea[name="body"]');

            // 氏名のバリデーション
            if (!nameInput.value.trim()) {
                errors.push("氏名は必須入力です。");
            } else if (nameInput.value.length > 10) {
                errors.push("10文字以内で入力してください。");
            }

            // フリガナのバリデーション
            if (!kanaInput.value.trim()) {
                errors.push("フリガナは必須入力です。");
            } else if (kanaInput.value.length > 10) {
                errors.push("10文字以内で入力してください。");
            }

            // メールアドレスのバリデーション
            if (!emailInput.value.trim()) {
                errors.push("メールアドレスは必須入力です。");
            } else {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailInput.value)) {
                    errors.push(
                        "メールアドレスは正しい形式でしか入力出来ません。",
                    );
                }
            }

            // お問い合わせ内容のバリデーション
            if (!bodyInput.value.trim()) {
                errors.push("お問い合わせ内容は必須入力です。");
            }

            // XSSチェック
            inputs.forEach((input) => {
                if (containsXSS(input.value)) {
                    errors.push(
                        "セキュリティ上の問題により、この入力は受け付けられません。",
                    );
                }
            });

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
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
                // XSSチェックをパスした場合、エラーを自動的にクリアしない
                // main.jsのバリデーションに任せる
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
