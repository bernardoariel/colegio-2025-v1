export declare class PdfService {
    generarComprobantePDF(data: {
        puntoVenta: number;
        tipoComprobante: number;
        numeroComprobante: number;
    }): Buffer;
}
