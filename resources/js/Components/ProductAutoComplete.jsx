import React, { useEffect, useRef, useState } from 'react';
import axios from 'axios';
import classNames from 'classnames';

const ProductAutoComplete = ({
                                 defaultValue = '',
                                 warehouseId,
                                 customerId,
                                 onProductSelect
                             }) => {
    const [term, setTerm] = useState(defaultValue);
    const [products, setProducts] = useState([]);
    const [selectedIndex, setSelectedIndex] = useState(-1);
    const [isOpen, setIsOpen] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [lastKeyTime, setLastKeyTime] = useState<number>(0);
    const [keyIntervals, setKeyIntervals] = useState<number[]>([]);

    const wrapperRef = useRef(null);
    const inputRef = useRef(null);

    const fetchSuggestions = async (query) => {
        if (!query) return;
        setIsLoading(true);
        try {
            const response = await axios.get('/suggestions', {
                params: {
                    term: query,
                    warehouse_id: warehouseId,
                    customer_id: customerId
                }
            });
            setProducts(response.data || []);
        } catch (error) {
            console.error('Failed to fetch product suggestions:', error);
        } finally {
            setIsLoading(false);
        }
    };

    const handleInputChange = (e) => {
        const value = e.target.value;
        setTerm(value);
        fetchSuggestions(value);
        setIsOpen(true);
    };

    const handleSelect = (product) => {
        setTerm(product.label);
        setIsOpen(false);
        onProductSelect(product);
    };

    const handleKeyDown = (e) => {
        if (e.key === 'ArrowDown') {
            setSelectedIndex((prev) => (prev + 1) % products.length);
        } else if (e.key === 'ArrowUp') {
            setSelectedIndex((prev) => (prev - 1 + products.length) % products.length);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (products[selectedIndex]) {
                handleSelect(products[selectedIndex]);
            }
        }
    };

    const handleClickOutside = (event) => {
        if (wrapperRef.current && !wrapperRef.current.contains(event.target)) {
            setIsOpen(false);
        }
    };

    useEffect(() => {
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    return (
        <div ref={wrapperRef} className="position-relative">
            <input
                ref={inputRef}
                type="text"
                value={term}
                onChange={handleInputChange}
                onKeyDown={handleKeyDown}
                className="form-control"
                placeholder="Scan barcode or type product name"
            />
            {isOpen && (
                <ul className="dropdown-menu show w-100 mt-1">
                    {isLoading ? (
                        <li className="dropdown-item disabled">Loading...</li>
                    ) : products.length ? (
                        products.map((product, index) => (
                            <li
                                key={product.id}
                                className={classNames('dropdown-item', {
                                    active: index === selectedIndex
                                })}
                                onClick={() => handleSelect(product)}
                            >
                                {product.label}
                            </li>
                        ))
                    ) : (
                        <li className="dropdown-item disabled">No products found</li>
                    )}
                </ul>
            )}
        </div>
    );
};

export default ProductAutoComplete;
