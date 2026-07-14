import './bootstrap';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Rich-text editor backed by Quill. It is exposed as `window.ClassicEditor`
// and the returned instance mirrors the small slice of the CKEditor 5 API the
// Blade pages relied on (`create().then()`, `getData`, `setData`,
// `model.document.on('change:data')`, `destroy`) so swapping the engine needs
// no changes in the views.
const TOOLBAR = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'link'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    [{ indent: '-1' }, { indent: '+1' }],
    ['blockquote'],
    ['clean'],
];

function createEditor(textarea, options = {}) {
    // Quill needs an element to mount into; the form field stays a hidden
    // <textarea> so jQuery/FormData reads keep working. Wrap the toolbar +
    // editor in a host element so teardown can remove both in one shot.
    const host = document.createElement('div');
    host.className = 'quill-host';
    const editorEl = document.createElement('div');
    host.appendChild(editorEl);
    textarea.insertAdjacentElement('afterend', host);

    const quill = new Quill(editorEl, {
        theme: 'snow',
        placeholder: options.placeholder || textarea.getAttribute('placeholder') || '',
        modules: { toolbar: TOOLBAR },
    });

    // Empty Quill content serialises to "<p><br></p>"; normalise that to an
    // empty string so server-side "required" validation behaves as expected.
    const read = () => (quill.getText().trim().length === 0 ? '' : quill.getSemanticHTML());
    const sync = () => { textarea.value = read(); };

    // Seed from whatever Blade printed into the textarea (old()/value).
    if (textarea.value) {
        quill.clipboard.dangerouslyPasteHTML(textarea.value);
    }
    sync();
    quill.on('text-change', sync);

    return {
        getData: read,
        setData: (html) => {
            quill.setContents([]);
            if (html) quill.clipboard.dangerouslyPasteHTML(html);
            sync();
        },
        model: {
            document: {
                on: (_event, callback) => quill.on('text-change', callback),
            },
        },
        destroy: () => {
            host.remove();
            return Promise.resolve();
        },
    };
}

window.ClassicEditor = {
    create: (textarea, options) => Promise.resolve(createEditor(textarea, options)),
};

// ── Global jQuery + DataTables (available as window.$ everywhere) ──────────
import $ from 'jquery';
import DataTable from 'datatables.net-dt';

window.$ = window.jQuery = $;
DataTable.use($); // Correct way to register jQuery with DataTables in ESM

import Swal from 'sweetalert2';
window.Swal = Swal;

import lottie from 'lottie-web';
window.lottie = lottie;

import './vendor/particles.js';

window.confirmDelete = function(form, title = 'Are you sure?', text = "You won't be able to revert this!") {
    window.dispatchEvent(new CustomEvent('open-confirm-modal', {
        detail: {
            title: title,
            message: text,
            confirmText: 'Yes, delete',
            cancelText: 'Cancel',
            onConfirm: () => {
                if (typeof form === 'function') {
                    form();
                } else if (form && typeof form.submit === 'function') {
                    form.submit();
                }
            }
        }
    }));
    return false;
};
