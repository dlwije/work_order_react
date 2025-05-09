import accounting from "accounting";

export function transformItemList(item, mode = "up") {
    return {
        col1: item.item?.code ?? "",
        col2: item.item?.name ?? "",
        col3: item.qty ?? "",
        col4: item.item?.unit?.name ?? "",
        col5: item.price ? accounting.formatNumber(item.price, 2) : "",
        col6: item.total ? accounting.formatNumber(item.total, 2) : "",
        col7: item.item_id ?? "",
        col8: item.id ?? "",
        col9: item.item?.tax_id ?? "",
        col10: item.item?.tax_rate ? accounting.formatNumber(item.item.tax_rate, 2) : "",
        col11: item.item?.is_taxable ?? "",
        col12: item.description ?? "",
        col13: item.item?.unit_id ?? "",
        col14: item.item?.type ?? "",
        col15: item.item?.discount_type ?? "",
        col16: item.item?.discount ?? "",
        col17: item.total_discount ? accounting.formatNumber(item.total_discount, 2) : "",
        col18: mode,
        col19: item.item?.category_id ?? "",
        col20: item.item?.subcategory_id ?? "",
        col21: item.item?.brand_id ?? "",
    };
}
