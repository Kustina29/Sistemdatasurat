import './bootstrap';
import { createWorker } from 'tesseract.js';

let ocrWorker;

function assetUrl(path) {
    return new URL(`/${path.replace(/^\/+/, '')}`, window.location.origin).toString();
}

async function getOcrWorker(onProgress) {
    if (!ocrWorker) {
        ocrWorker = await createWorker('ind+eng', 1, {
            workerPath: assetUrl('tesseract/worker.min.js'),
            corePath: assetUrl('tesseract/tesseract-core-simd-lstm.wasm.js'),
            langPath: assetUrl('tessdata'),
            gzip: true,
            logger: (message) => {
                if (typeof onProgress === 'function') {
                    onProgress(message);
                }
            },
        });
    }

    return ocrWorker;
}

window.SipasOcr = {
    async recognize(file, onProgress) {
        const worker = await getOcrWorker(onProgress);
        const result = await worker.recognize(file);

        return result.data.text || '';
    },
};
