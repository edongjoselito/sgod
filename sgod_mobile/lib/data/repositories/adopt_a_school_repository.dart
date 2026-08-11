import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../ui/core/di.dart';
import '../models/partner_item.dart';
import '../models/donation_item.dart';
import '../models/tax_incentive_requirement_item.dart';

/// Repository for the Adopt-A-School feature — partners, donations,
/// and tax incentive requirements.
class AdoptASchoolRepository {
  AdoptASchoolRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _partnersKey = 'aas_partners';
  static const _donationsKey = 'aas_donations';

  // ── Partners ──────────────────────────────────────────────────────────────

  Future<List<PartnerItem>> fetchPartners({String search = ''}) async {
    try {
      final query = <String, dynamic>{'limit': 200};
      if (search.isNotEmpty) query['search'] = search;
      final data = await _api.get('api/partners_index', query: query);
      if (data == null) return const [];
      final items = (data as List<dynamic>)
          .map((e) => PartnerItem.fromJson(e as Map<String, dynamic>))
          .toList();
      _cache?.set(_partnersKey, items);
      if (_db != null && items.isNotEmpty) {
        try {
          await _db.cacheList(_partnersKey, items.map((e) => e.toJson()).toList());
        } catch (_) {}
      }
      return items;
    } catch (e) {
      if (_db != null) {
        try {
          final cached = await _db.getCachedList(_partnersKey);
          if (cached != null && cached.isNotEmpty) {
            return cached.map((e) => PartnerItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<PartnerItem>>(_partnersKey);
      if (memCached != null) return memCached;
      rethrow;
    }
  }

  Future<String> savePartner(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await DI.write(
      endpoint: 'partners_save',
      entity: 'partners',
      operation: id.isNotEmpty ? 'update' : 'create',
      payload: body,
    );
    _cache?.remove(_partnersKey);
    return (data?['id'] ?? 0).toString();
  }

  Future<void> deletePartner(String id) async {
    await DI.write(
      endpoint: 'partners_delete',
      entity: 'partners',
      operation: 'delete',
      payload: {'id': id},
    );
    _cache?.remove(_partnersKey);
  }

  // ── Donations ─────────────────────────────────────────────────────────────

  Future<List<DonationItem>> fetchDonations({String partnerId = ''}) async {
    try {
      final query = <String, dynamic>{'limit': 200};
      if (partnerId.isNotEmpty) query['partner_id'] = partnerId;
      final data = await _api.get('api/donations_index', query: query);
      if (data == null) return const [];
      final items = (data as List<dynamic>)
          .map((e) => DonationItem.fromJson(e as Map<String, dynamic>))
          .toList();
      _cache?.set(_donationsKey, items);
      if (_db != null && items.isNotEmpty) {
        try {
          await _db.cacheList(_donationsKey, items.map((e) => e.toJson()).toList());
        } catch (_) {}
      }
      return items;
    } catch (e) {
      if (_db != null) {
        try {
          final cached = await _db.getCachedList(_donationsKey);
          if (cached != null && cached.isNotEmpty) {
            return cached.map((e) => DonationItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<DonationItem>>(_donationsKey);
      if (memCached != null) return memCached;
      rethrow;
    }
  }

  Future<String> saveDonation(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await DI.write(
      endpoint: 'donations_save',
      entity: 'donations',
      operation: id.isNotEmpty ? 'update' : 'create',
      payload: body,
    );
    _cache?.remove(_donationsKey);
    return (data?['id'] ?? 0).toString();
  }

  Future<void> deleteDonation(String id) async {
    await DI.write(
      endpoint: 'donations_delete',
      entity: 'donations',
      operation: 'delete',
      payload: {'id': id},
    );
    _cache?.remove(_donationsKey);
  }

  // ── Tax Incentive Requirements ────────────────────────────────────────────

  Future<List<TaxIncentiveRequirementItem>> fetchTaxRequirements(
      String donationId) async {
    final data = await _api.get(
      'api/tax_incentive_requirements_index',
      query: {'donation_id': donationId},
    );
    if (data == null) return const [];
    return (data as List<dynamic>)
        .map((e) => TaxIncentiveRequirementItem.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<String> saveTaxRequirement(Map<String, dynamic> fields,
      {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await DI.write(
      endpoint: 'tax_incentive_requirements_save',
      entity: 'tax_requirements',
      operation: id.isNotEmpty ? 'update' : 'create',
      payload: body,
    );
    return (data?['id'] ?? 0).toString();
  }

  Future<void> deleteTaxRequirement(String id) async {
    await DI.write(
      endpoint: 'tax_incentive_requirements_delete',
      entity: 'tax_requirements',
      operation: 'delete',
      payload: {'id': id},
    );
  }

  // ── Contribution Types ────────────────────────────────────────────────────

  Future<List<String>> fetchContributionTypes() async {
    final data = await _api.get('api/contribution_types_index');
    if (data == null) return const [];
    return (data as List<dynamic>)
        .map((e) => (e as Map<String, dynamic>)['name'] as String? ?? '')
        .where((s) => s.isNotEmpty)
        .toList();
  }
}
