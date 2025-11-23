document.addEventListener('DOMContentLoaded', () => {
    const loanSelect = document.querySelector('#loan_id');
    const amountInput = document.querySelector('#amount');
    const installmentInput = document.querySelector('#installment_count');
    const rateInput = document.querySelector('#rate');
    const totalField = document.querySelector('#total');
    const totalInstallmentField = document.querySelector('#totalInstallment');
    const totalSavingField = document.querySelector('#totalSaving');
    const subFormBtn = document.querySelector('#subForm');
    const backOverlay = document.querySelector('#back');
    const downRules = document.querySelector('.down-rules');
    const paymentTypeField = document.querySelector('#paymentType');
    const accountPriceField = document.querySelector('#accountPrice');
    const accountNameFields = document.querySelectorAll('#accountName');
    const accountProfitFields = document.querySelectorAll('#accountPorift');

    let paymentTypeID = 0;
    let selectedLoan = null;

    // ✅ نمایش مبلغ در باکس تایید
    amountInput.addEventListener('input', e => {
        accountPriceField.innerHTML = e.target.value;
    });

    // ✅ تغییر محتوای نام و نرخ سود با انتخاب وام
    loanSelect.addEventListener('input', e => {
        const selectedOption = e.target.options[e.target.selectedIndex];
        selectedLoan = {
            id: selectedOption.value,
            name: selectedOption.getAttribute('name'),
            profit: selectedOption.getAttribute('profit'),
            maxAmount: selectedOption.getAttribute('amount'),
            maxInstallments: selectedOption.getAttribute('max-installment'),
            increaseRate: selectedOption.getAttribute('rate-of-increase')
        };

        updateAccountInfo(selectedLoan);
        resetInputs(selectedLoan);
        attachCalculations(selectedLoan);
    });

    // ✅ دکمه تأیید اولیه فرم
    subFormBtn.addEventListener('click', () => {
        if (!selectedLoan || !amountInput.value || !installmentInput.value || !rateInput.value) {
            alert('ابتدا مقادیر خواسته شده را وارد کنید');
            return;
        }

        let allowShow = false;
        let paymentTypeText = '';

        if (paymentTypeID === 1) {
            const card = document.querySelector('input[type=radio]:checked');
            if (!card) {
                alert('ابتدا یک کارت انتخاب کنید');
                return;
            }
            paymentTypeText = `واریز وجه به شماره کارت ${card.getAttribute('cart-number')} به نام ${card.getAttribute('owner')}`;
            allowShow = true;
        } else if (paymentTypeID === 2) {
            const person = document.querySelector('.allowedPerson');
            paymentTypeText = `دریافت وجه نقد به گیرنده با نام ${person.options[person.selectedIndex].text}`;
            allowShow = true;
        } else {
            paymentTypeText = 'پرداخت از طریق درگاه پرداخت';
            allowShow = true;
        }

        if (allowShow) {
            paymentTypeField.innerHTML = paymentTypeText;
            backOverlay.style.display = 'block';
            downRules.style.display = 'block';
        }
    });

    // ✅ دکمه اصلاح درخواست
    window.editRequest = function () {
        backOverlay.style.display = 'none';
        downRules.style.display = 'none';
    };

    // ✅ نمایش اطلاعات نام و سود وام در باکس تأیید
    function updateAccountInfo(loan) {
        accountNameFields.forEach(el => el.innerHTML = loan.name || '');
        accountProfitFields.forEach(el => el.innerHTML = loan.profit ? loan.profit + '%' : '');
    }

    // ✅ بازنشانی و فعال‌سازی ورودی‌ها
    function resetInputs(loan) {
        if (!loan.maxAmount || !loan.maxInstallments) return;

        amountInput.removeAttribute('disabled');
        installmentInput.removeAttribute('disabled');
        rateInput.removeAttribute('disabled');

        amountInput.value = '';
        installmentInput.value = '';
        rateInput.value = loan.increaseRate;

        document.querySelector('#amountVal').innerHTML = `حداکثر: (${formatNumber(loan.maxAmount)} تومان)`;
        document.querySelector('#installmentVal').innerHTML = `حداکثر: (${loan.maxInstallments} قسط)`;
    }

    // ✅ محاسبات مبلغ، قسط و سود اجباری
    function attachCalculations(loan) {
        const maxAmount = parseInt(loan.maxAmount);
        const maxInstallments = parseInt(loan.maxInstallments);

        amountInput.addEventListener('input', () => calculateFields());
        installmentInput.addEventListener('input', () => calculateFields());
        rateInput.addEventListener('input', () => calculateFields());

        function calculateFields() {
            const amount = parseInt(amountInput.value.replace(/,/g, ''));
            const installments = parseInt(installmentInput.value);
            const rate = parseFloat(rateInput.value);

            if (isNaN(amount) || isNaN(installments) || isNaN(rate)) return;

            // اعتبارسنجی مقدار واردشده
            amountInput.style.border = amount > maxAmount ? '1px solid red' : '1px solid #dee2e6';
            installmentInput.style.border = installments > maxInstallments ? '1px solid red' : '1px solid #dee2e6';

            const total = amount + (amount * rate / 100);
            const perInstallment = total / installments;
            const saving = total - amount;

            totalField.value = formatNumber(total);
            totalInstallmentField.value = formatNumber(perInstallment);
            totalSavingField.value = formatNumber(saving);
        }
    }

    // ✅ فرمت اعداد به تومان
    function formatNumber(number) {
        return new Intl.NumberFormat('fa-IR', {
            style: 'currency',
            currency: 'IRR',
            maximumFractionDigits: 0
        }).format(number);
    }
});
