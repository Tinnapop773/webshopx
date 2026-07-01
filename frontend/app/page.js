'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import useAuthStore from '@/lib/store';
import createAxiosInstance from '@/lib/axios';

export default function Home() {
  const { user, token } = useAuthStore();
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [siteConfig, setSiteConfig] = useState(null);

  useEffect(() => {
    fetchProducts();
    fetchConfig();
  }, []);

  const fetchProducts = async () => {
    try {
      const axiosInstance = createAxiosInstance(token);
      const res = await axiosInstance.get('/products?limit=8');
      setProducts(res.data.products);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching products:', error);
      setLoading(false);
    }
  };

  const fetchConfig = async () => {
    try {
      const axiosInstance = createAxiosInstance(token);
      const res = await axiosInstance.get('/config');
      setSiteConfig(res.data.config);
    } catch (error) {
      console.error('Error fetching config:', error);
    }
  };

  return (
    <div className="min-h-screen" style={{ backgroundColor: '#f5f5f5' }}>
      {/* Header/Navbar */}
      <header className="bg-white shadow" style={{ borderBottom: `3px solid ${siteConfig?.primaryColor || '#3498db'}` }}>
        <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
          <div className="flex items-center space-x-2">
            <h1 className="text-2xl font-bold" style={{ color: siteConfig?.primaryColor || '#3498db' }}>
              {siteConfig?.siteName || 'WebShopX'}
            </h1>
          </div>
          
          <div className="flex items-center space-x-4">
            <input
              type="text"
              placeholder="ค้นหาสินค้า..."
              className="px-4 py-2 border rounded-lg focus:outline-none"
              style={{ borderColor: siteConfig?.primaryColor || '#3498db' }}
            />
            
            {user ? (
              <>
                <Link href="/cart" className="text-gray-700 hover:text-gray-900">🛒 ตระกร้า</Link>
                <Link href="/orders" className="text-gray-700 hover:text-gray-900">📦 คำสั่งซื้อ</Link>
                <Link href="/profile" className="text-gray-700 hover:text-gray-900">👤 {user.username}</Link>
              </>
            ) : (
              <>
                <Link href="/auth/login" className="text-gray-700 hover:text-gray-900">เข้าสู่ระบบ</Link>
                <Link href="/auth/register" className="px-4 py-2 rounded text-white" style={{ backgroundColor: siteConfig?.primaryColor || '#3498db' }}>สมัครสมาชิก</Link>
              </>
            )}
          </div>
        </nav>
      </header>

      {/* Hero Banner */}
      <section className="py-12 text-center text-white" style={{ backgroundColor: siteConfig?.primaryColor || '#3498db' }}>
        <h2 className="text-4xl font-bold mb-4">ยินดีต้อนรับ {siteConfig?.siteName || 'WebShopX'}</h2>
        <p className="text-xl mb-6">ค้นหาสินค้าที่คุณชื่นชอบจากเรา</p>
      </section>

      {/* Products Section */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h3 className="text-2xl font-bold mb-8 text-gray-800">🔥 สินค้าแนะนำ</h3>
        
        {loading ? (
          <div className="text-center py-12">Loading...</div>
        ) : products.length === 0 ? (
          <div className="text-center py-12 text-gray-500">ไม่มีสินค้า</div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {products.map((product) => (
              <div key={product._id} className="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
                <img src={product.image} alt={product.name} className="w-full h-40 object-cover rounded mb-4" />
                <h4 className="font-bold text-lg mb-2">{product.name}</h4>
                <p className="text-gray-600 text-sm mb-4 line-clamp-2">{product.description}</p>
                <div className="flex justify-between items-center">
                  <span className="text-xl font-bold" style={{ color: siteConfig?.primaryColor || '#3498db' }}>
                    ฿{product.price.toLocaleString()}
                  </span>
                  <button 
                    className="px-4 py-2 rounded text-white text-sm"
                    style={{ backgroundColor: siteConfig?.secondaryColor || '#2ecc71' }}
                  >
                    ซื้อเลย
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </section>

      {/* Footer */}
      <footer className="bg-gray-800 text-white py-8 mt-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-3 gap-8 mb-8">
            <div>
              <h4 className="font-bold mb-4">เกี่ยวกับเรา</h4>
              <p className="text-gray-400 text-sm">WebShopX เป็นแพลตฟอร์มขายสินค้าออนไลน์แบบครบครัน</p>
            </div>
            <div>
              <h4 className="font-bold mb-4">ลิงค์ด่วน</h4>
              <ul className="text-gray-400 text-sm space-y-2">
                <li><a href="#" className="hover:text-white">สินค้า</a></li>
                <li><a href="#" className="hover:text-white">ติดต่อเรา</a></li>
                <li><a href="#" className="hover:text-white">นโยบายส่วนตัว</a></li>
              </ul>
            </div>
            <div>
              <h4 className="font-bold mb-4">ติดต่อ</h4>
              <p className="text-gray-400 text-sm">📧 info@webshopx.com</p>
              <p className="text-gray-400 text-sm">📱 080-xxx-xxxx</p>
            </div>
          </div>
          <div className="border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; 2024 {siteConfig?.siteName || 'WebShopX'}. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
}
